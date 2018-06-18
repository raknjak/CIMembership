<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_password extends Auth_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('new_password_model');
    }

    public function _remap($method, $params = array()) {

        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }

        // email validation
        if (! $this->form_validation->is_valid_email(urldecode($this->uri->segment(2)))) {
            $this->session->set_flashdata('error', $this->lang->line('new_password_error_email'));
            redirect('login');
        }

        // token validation
        if (! $this->form_validation->alpha_numeric($this->uri->segment(3))
            || !$this->form_validation->exact_length($this->uri->segment(3), 40)) {
            $this->session->set_flashdata('error', $this->lang->line('new_password_error_token'));
            redirect ('login');
        }

        if (!$result = $this->new_password_model->check_token()) {
            $this->session->set_flashdata('error', $this->lang->line('new_password_error_db'));
            redirect('login');
        }

        if ($result == "expired") {
            $this->session->set_flashdata('error', $this->lang->line('new_password_expired'));
            redirect('login');
        }

        $this->template->set_js('clipboard', base_url() .'assets/vendor/clipboard/clipboard.min.js');

        $this->template->set_js('big-min', base_url() .'assets/vendor/diceware/components/big.min.js');
        $this->template->set_js('special-min', base_url() .'assets/vendor/diceware/lists/special-min.js');
        $this->template->set_js('diceware-min', base_url() .'assets/vendor/diceware/lists/diceware-min.js');
        $this->template->set_js('eff', base_url() .'assets/vendor/diceware/lists/eff.js');
        $this->template->set_js('password-gen', base_url() .'assets/vendor/diceware/password_generator.js');

        $content_data['token'] = $result->token;

        // set a flashdata to avoid abuse
        $this->session->set_flashdata('temp_user_id', $result->user_id);
        $this->session->set_flashdata('temp_token', $content_data['token']);

        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main',  $this->lang->line('new_password_title'), 'new_password', 'header', 'footer', '', $content_data);

    }

    public function change_password() {
        // check flashdata
        if ($this->session->flashdata('temp_token') == "" || $this->session->flashdata('temp_user_id') == "") {
            $this->session->set_flashdata('error', $this->lang->line('new_password_no_flash'));
            redirect('login');
        }

        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('password', $this->lang->line('new_password_password'), 'trim|required|max_length[255]|min_length[9]|is_valid_password');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('login');
        }

        if ($this->new_password_model->change_password($this->input->post('password'))) {
            $this->session->set_flashdata('success', $this->lang->line('new_password_done'));
        }else{
            $this->session->set_flashdata('error', $this->lang->line('new_password_fail'));
        }

        redirect('login');
    }

}



