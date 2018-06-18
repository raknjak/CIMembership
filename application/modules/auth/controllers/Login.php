<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Auth_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        if (Settings_model::$db_config['recaptchav2_enabled'] == 1) {
            $this->load->library('recaptchaV2');
        }
    }

    public function index() {

        $data = array();

        // if OAuth is enabled show providers
        if (Settings_model::$db_config['oauth_enabled']) {
            // generate all active OAuth Providers
            $this->load->model('auth/Oauth_model');
            $data['providers'] = $this->Oauth_model->get_all_providers();
        }

        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main',  $this->lang->line('login'), 'login', 'header', 'footer', '', $data);
    }

    /**
     *
     * validate: validate login after input fields have met requirements
     *
     *
     */
    public function validate() {

        if ($this->session->userdata('login_attempts') == false) {
            $v = 0;
        }else{
            $v = $this->session->userdata('login_attempts');
        }

        if ($this->form_validation->is_valid_email($this->input->post('identification')) && Settings_model::$db_config['allow_login_by_email']) {
            // email is valid and allowed
            $this->form_validation->set_rules('identification', $this->lang->line('login_identification'), 'trim|required|max_length[255]|is_valid_email');
        }else{
            // only username is allowed
            $this->form_validation->set_rules('identification', $this->lang->line('login_identification'), 'trim|required|min_length[6]|max_length[24]|is_valid_username');
        }

        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('password', $this->lang->line('login_password'), 'trim|required|min_length[9]|max_length[255]|strip_tags');
        if ($v >= Settings_model::$db_config['login_attempts'] && Settings_model::$db_config['recaptchav2_enabled'] == true) {
            $this->form_validation->set_rules('g-recaptcha-response', $this->lang->line('recaptchav2_response'), 'required|check_captcha');
        }

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('login');
        }

        $this->load->model('auth/login_model');

        // check max login attempts first
        if ($this->login_model->check_max_logins($this->input->post('username'))) {
            $this->session->set_flashdata('error', $this->lang->line('max_login_attempts_reached'));
            redirect('login');
        }

        // database work
        $userData = $this->login_model->validate_login($this->input->post('identification'), $this->input->post('password'), Settings_model::$db_config['allow_login_by_email']);

        if (is_object($userData)) {
            if ($userData->banned == true) { // check banned
                $this->session->set_flashdata('error', $this->lang->line('account_access_denied'));
                redirect('login');
            }elseif (Settings_model::$db_config['registration_approval_required'] === true && $userData->approved == false) {
                $this->session->set_flashdata('error', $this->lang->line('login_needs_approval'));
                redirect('login');
            }elseif (Settings_model::$db_config['registration_activation_required'] === true && $userData->active == false) { // check active
                $this->session->set_flashdata('error', $this->lang->line('account_activate'));
                redirect('login');
            }else{

                // user is fine, now load roles and set session data
                $this->permissions_roles($userData->user_id);

                // let administrators through, the other roles will be redirected when checks below match
                if (!self::check_roles(1)) {
                    if(Settings_model::$db_config['login_enabled'] == 0) {
                        $this->session->set_flashdata('error', $this->lang->line('login_disabled'));
                        redirect('login');
                    }
                }

                // set the cookie if remember me option is set
                $this->load->helper('cookie');
                $cookie_domain = config_item('cookie_domain');
                if ($this->input->post('remember_me') && !get_cookie('unique_token') && Settings_model::$db_config['remember_me_enabled'] == true) {
                    setcookie("unique_token", md5(uniqid(mt_rand(), true)) . substr(uniqid(mt_rand(), true), -10) . $userData->cookie_part, time() + Settings_model::$db_config['cookie_expires'], '/', $cookie_domain, false, false);
                }

                // set session data
                $this->load->helper('session');
                session_init($userData);

                $this->login_model->reset_login_attempts($userData->username);
                $this->session->set_userdata('login_attempts', 0);

                // redirect to previous page
                if ($this->input->post('previous_url') != "" && base64_decode($this->input->post('previous_url')) != base_url() && Settings_model::$db_config['previous_url_after_login'] == true) {
                    redirect(base64_decode($this->input->post('previous_url')));
                }

                redirect('membership/'. strtolower(Settings_model::$db_config['home_page']));
            }
        }else{
            $this->session->set_flashdata('error', $this->lang->line('login_incorrect'));
            $this->session->set_userdata('login_attempts', $userData);
            redirect('login');
        }
    }

}
