<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth_providers extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('adminpanel/oauth_providers_model');
        $this->load->library('encryption');

        if (! self::check_permissions(7)) {
            redirect("/adminpanel/no_access");
        }
    }

    public function index() {
        $content_data['providers'] = $this->oauth_providers_model->get_providers();
        $content_data['enabled'] = array('1' => 'Yes', '0' => 'No');
        $content_data['enabled_selected'] = '0';

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('oauth_providers_title'),
            'oauth_providers',
            $this->_header,
            $this->_footer,
            '',
            $content_data);
    }

    /**
     *
     * action: used to handle both save and delete below
     *
     */

    public function action() {
        if (isset($_POST['delete'])) {
            $this->_delete();
        }else{ // delete needs to be sent or else it will always save, for example when hitting enter on keyboard
            $this->_save();
        }
    }

    /**
     *
     * _save: store provider data
     *
     */

    private function _save() {
        if ($this->input->post('oauth_provider_id') != strval(intval($this->input->post('oauth_provider_id')))) {
            redirect('/adminpanel/oauth_providers');
        }

        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('oauth_order', $this->lang->line('provider_name'), 'trim|required|integer');
        $this->form_validation->set_rules('name', $this->lang->line('provider_name'), 'trim|required|max_length[50]|min_length[2]');
        $this->form_validation->set_rules('client_id', $this->lang->line('provider_client_id'), 'trim|required|max_length[255]|min_length[2]');
        $this->form_validation->set_rules('client_secret', $this->lang->line('provider_client_secret'), 'trim|required|max_length[255]|min_length[2]');
        $this->form_validation->set_rules('oauth_type', $this->lang->line('oauth_type'), 'trim|required|is_natural');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/oauth_providers');
            exit();
        }

        $data = array(
            'oauth_provider_id' => $this->input->post('oauth_provider_id'),
            'name' => $this->input->post('name'),
            'client_id' => $this->encryption->encrypt($this->input->post('client_id')),
            'client_secret' => $this->encryption->encrypt($this->input->post('client_secret')),
            'enabled' => $this->input->post('enabled') == 1 ? true : false,
            'oauth_order' => $this->input->post('oauth_order'),
            'oauth_type' => $this->input->post('oauth_type')
        );

        if ($this->oauth_providers_model->save_provider($data)) {
            $this->session->set_flashdata('success', $this->lang->line('provider_saved'));
        }

        redirect('/adminpanel/oauth_providers');
    }

    /**
     *
     * _delete: remove provider data
     *
     */

    private function _delete() {
        if ($this->input->post('oauth_provider_id') != strval(intval($this->input->post('oauth_provider_id')))) {
            redirect('/adminpanel/oauth_providers');
        }

        if ($this->oauth_providers_model->delete_provider($this->input->post('oauth_provider_id'))) {
            $this->session->set_flashdata('success', $this->lang->line('provider_deleted'));
        }

        redirect('/adminpanel/oauth_providers');
    }

}
