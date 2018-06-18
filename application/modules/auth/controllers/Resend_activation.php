<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resend_activation extends Auth_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'send_email'));
        $this->load->library('form_validation');
        if (Settings_model::$db_config['recaptchav2_enabled'] == 1) {
            $this->load->library('recaptchaV2');
        }
    }

    public function index() {
        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('resend_activation_title'), 'resend_activation', 'header', 'footer');
    }

    /**
     *
     * send_link: resend activation link
     *
     *
     */

    public function send_link() {
        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('email', $this->lang->line('resend_activation_email_address'), 'trim|required|is_valid_email');
        if (Settings_model::$db_config['recaptchav2_enabled'] == true) {
            $this->form_validation->set_rules('g-recaptcha-response', $this->lang->line('recaptchav2_response'), 'required|check_captcha');
        }

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('resend_activation');
            exit();
        }

        $this->load->model('auth/data_by_email_model');
        $data = $this->data_by_email_model->get_data_by_email($this->input->post('email'));

        if ($data['active']) {
            $this->session->set_flashdata('error', $this->lang->line('account_active'));
            redirect('resend_activation');

        }elseif (!empty($data['cookie_part'])) {

            $this->load->model('auth/resend_activation_model');
            $this->resend_activation_model->update_last_login($data['username']);
            $this->load->helper('send_email');
            $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
            $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
            $this->email->to($this->input->post('email'));
            $this->email->set_mailtype("html");

            if (Settings_model::$db_config['registration_activation_required']) {

                $this->email->subject($this->lang->line('resend_activation_subject'));

                $data['email'] = $this->input->post('email');

                $this->email->message(
                    $this->load->view('generic/email_templates/header.php', array('new_username' => $data['username']), true) .
                    $this->load->view('themes/bootstrap3/email_templates/resend-activation.php', $data, true) .
                    $this->load->view('generic/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );
                $this->email->set_alt_message(
                    $this->load->view('generic/email_templates/header-txt.php', array('new_username' => $data['username']), true) .
                    $this->load->view('themes/bootstrap3/email_templates/resend-activation-txt.php', $data, true) .
                    $this->load->view('generic/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );

                if ($this->email->send()) {
                    $this->session->set_flashdata('success', $this->lang->line('resend_activation_success'));
                }else{
                    $this->session->set_flashdata('error', $this->lang->line('email_send_false'));
                }
            }else{
                $this->email->subject($this->lang->line('resend_activation_email_active_subject'));
                $this->email->message(sprintf($this->lang->line('resend_activation_email_active_message'), $data['username']));
                $this->email->send();
                $this->session->set_flashdata('success', $this->lang->line('resend_activation_email_active_success'));
            }

            redirect('resend_activation');

        }else{
            $this->session->set_flashdata('error', $this->lang->line('email_not_found'));
        }

        $this->session->set_flashdata('email', $this->input->post('email'));
        redirect('resend_activation');
    }

}
