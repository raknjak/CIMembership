<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Oauth_new_provider extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('adminpanel/oauth_new_provider_model');

        if (! self::check_permissions(7)) {
            redirect("/adminpanel/no_access");
        }
    }

    public function index() {
        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('oauth_new_provider_title'),
            'oauth_new_provider',
            $this->_header,
            $this->_footer
            );
    }

    /**
     *
     * save: store new oauth provider
     *
     */

    public function save() {
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('name', $this->lang->line('provider_name'), 'trim|required|max_length[50]|min_length[2]|is_db_cell_available[oauth_provider.name]');
        $this->form_validation->set_rules('client_id', $this->lang->line('provider_client_id'), 'trim|required|max_length[255]|min_length[2]');
        $this->form_validation->set_rules('client_secret', $this->lang->line('provider_client_secret'), 'trim|required|max_length[255]|min_length[2]');
        $this->form_validation->set_rules('oauth_type', $this->lang->line('oauth_type'), 'trim|required|is_natural');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            $this->session->set_flashdata($_POST);
            redirect('/adminpanel/oauth_new_provider');
            exit();
        }

        $this->load->library('encryption');

        $data = array(
            'name' => ucfirst($this->input->post('name')),
            'client_id' => $this->encryption->encrypt($this->input->post('client_id')),
            'client_secret' => $this->encryption->encrypt($this->input->post('client_secret')),
            'enabled' => ($this->input->post('enabled') != "" ? true : false),
            'oauth_type' => $this->input->post('oauth_type')
        );

        if ($this->oauth_new_provider_model->save_provider($data)) {
            $this->session->set_flashdata('success', $this->lang->line('provider_success_add'));
        }

        redirect('/adminpanel/oauth_new_provider');
    }

}