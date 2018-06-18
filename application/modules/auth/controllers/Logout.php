<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->session->set_flashdata('success', $this->lang->line('logout_msg'));
        $this->load->helper('session');
        unset_session_data();
        redirect('login');
    }

    public function index() {}

}