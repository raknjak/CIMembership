<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends Auth_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        if (Settings_model::$db_config['recaptchav2_enabled'] == true) {
            $this->load->library('recaptchaV2');
        }
    }

    public function index() {
        $data = array();

        // if OAuth2 enabled
        if (Settings_model::$db_config['oauth_enabled']) {
            // generate all active OAuth Providers
            $this->load->model('auth/Oauth_model');
            $data['providers'] = $this->Oauth_model->get_all_providers();
        }

        if (Settings_model::$db_config['registration_requires_password']) {

            $this->template->set_js('clipboard', base_url() .'assets/vendor/clipboard/clipboard.min.js');

            $this->template->set_js('big-min', base_url() .'assets/vendor/diceware/components/big.min.js');
            $this->template->set_js('special-min', base_url() .'assets/vendor/diceware/lists/special-min.js');
            $this->template->set_js('diceware-min', base_url() .'assets/vendor/diceware/lists/diceware-min.js');
            $this->template->set_js('eff', base_url() .'assets/vendor/diceware/lists/eff.js');
            $this->template->set_js('password-gen', base_url() .'assets/vendor/diceware/password_generator.js');
        }

        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('register_title'), 'register', 'header', 'footer', '', $data);
    }

    /**
     *
     * add_member: insert a new member into the database after all input fields have met the requirements
     *
     *
     */

    public function add_member() {
        // check whether creating member is allowed
        if (Settings_model::$db_config['register_enabled'] == 0) {
            $this->session->set_flashdata('error', $this->lang->line('registration_disabled'));
            redirect('register');
        }

        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('first_name', $this->lang->line('register_first_name'), 'trim|required|max_length[40]|min_length[2]');
        $this->form_validation->set_rules('last_name', $this->lang->line('register_last_name'), 'trim|required|max_length[60]|min_length[2]');
        $this->form_validation->set_rules('email', $this->lang->line('register_email_address'), 'trim|required|max_length[254]|is_valid_email|is_db_cell_available[user.email]');

        if (Settings_model::$db_config['registration_requires_username']) {
            $this->form_validation->set_rules('username', $this->lang->line('register_username'), 'trim|required|max_length[24]|min_length[6]|is_valid_username|is_db_cell_available[user.username]');
        }else{
            $new_username = str_replace("@", "-", $this->input->post('email'));
        }

        if (Settings_model::$db_config['registration_requires_password']) {
            $this->form_validation->set_rules('password', $this->lang->line('register_password'), 'trim|required|max_length[255]|min_length[9]|is_valid_password');
            $this->form_validation->set_rules('password_confirm', $this->lang->line('register_password_confirm'), 'trim|required|max_length[255]|min_length[9]|matches[password]');
        }else{
            $new_password = password_hash(md5(uniqid(time())), PASSWORD_DEFAULT);
        }

        if (Settings_model::$db_config['recaptchav2_enabled'] == true) {
            $this->form_validation->set_rules('g-recaptcha-response', $this->lang->line('recaptchav2_response'), 'required|check_captcha');
        }

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            $this->session->set_flashdata($_POST);
            redirect('register');
        }

        $this->load->model('auth/register_model');

        $actual_username = (isset($new_username) ? $new_username : $this->input->post('username'));

        if ($return_array = $this->register_model->create_member(
            $actual_username,
            (isset($new_password) ? $new_password : $this->input->post('password')),
            $this->input->post('email'),
            $this->input->post('first_name'),
            $this->input->post('last_name'),
            (Settings_model::$db_config['registration_activation_required'] == 0 ? true : false))
        ) {

            // add default member role
            $this->load->model('utils/rbac_model');
            $this->rbac_model->create_user_role(array('user_id' => $return_array['user_id'], 'role_id' => 4));

            // create directory
            if (!file_exists(FCPATH .'assets/img/members/'. $actual_username)) {
                mkdir(FCPATH .'assets/img/members/'. $actual_username);
            }else{
                $this->session->set_flashdata('error', $this->lang->line('create_imgfolder_failed'));
                redirect('register');
            }

            // send confirmation email
            $this->load->helper('send_email');
            $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
            $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
            $this->email->to($this->input->post('email'));
            $this->email->set_mailtype("html");

            if (Settings_model::$db_config['registration_activation_required']) {

                $this->email->subject($this->lang->line('register_email_activation_subject'));

                $data = array(
                    'email' => $this->input->post('email'),
                    'cookie_part' => $return_array['cookie_part']
                    );

                $this->email->message(
                    $this->load->view('generic/email_templates/header.php', array('username' => $actual_username), true) .
                    $this->load->view('themes/bootstrap3/email_templates/register-activation.php', $data, true) .
                    $this->load->view('generic/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );
                $this->email->set_alt_message(
                    $this->load->view('generic/email_templates/header-txt.php', array('username' => $actual_username), true) .
                    $this->load->view('themes/bootstrap3/email_templates/register-activation-txt.php', $data, true) .
                    $this->load->view('generic/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );

                $this->email->send();
                $this->session->set_flashdata('success', $this->lang->line('register_email_success'));

            }elseif (Settings_model::$db_config['registration_approval_required']) {
                $this->email->subject($this->lang->line('register_email_approve_subject'));

                $this->email->message(
                    $this->load->view('generic/email_templates/header.php', array('username' => $actual_username), true) .
                    $this->load->view('themes/bootstrap3/email_templates/register-approval.php', '', true) .
                    $this->load->view('v/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );
                $this->email->set_alt_message(
                    $this->load->view('generic/email_templates/header-txt.php', array('username' => $actual_username), true) .
                    $this->load->view('themes/bootstrap3/email_templates/register-approval-txt.php', '', true) .
                    $this->load->view('generic/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );
                $this->email->send();
                $this->session->set_flashdata('success', $this->lang->line('register_email_approve_success'));

            }else{
                $this->email->subject($this->lang->line('register_email_active_subject'));

                $this->email->message(
                    $this->load->view('generic/email_templates/header.php', array('username' => $actual_username), true) .
                    $this->load->view('themes/bootstrap3/email_templates/register-active.php', '', true) .
                    $this->load->view('generic/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );
                $this->email->set_alt_message(
                    $this->load->view('generic/email_templates/header-txt.php', array('username' => $actual_username), true) .
                    $this->load->view('themes/bootstrap3/email_templates/register-active-txt.php', '', true) .
                    $this->load->view('generic/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );

                $this->email->send();
                $this->session->set_flashdata('success', $this->lang->line('register_email_active_success'));
            }
            redirect('login');

        }else{
            $this->session->set_flashdata('error', $this->lang->line('register_failed_db'));
            redirect('register');
        }

    }
    
}
