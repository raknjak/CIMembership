<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        if (! self::check_permissions(13)) {
            redirect("adminpanel/no_access");
        }
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('adminpanel/permissions_model');
    }

    public function index(){
        $content_data['permissions'] = $this->permissions_model->get_permissions();

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('permissions_title'),
            'permissions',
            $this->_header,
            $this->_footer,
            '',
            $content_data
        );
    }

    /**
     *
     * permissions_multi: sorting method that triggers private method depending on input
     *
     */

    public function permissions_multi() {
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('permission_id', 'id', 'trim|required|integer');
        $this->form_validation->set_rules('permission_description', $this->lang->line('permission_description'), 'trim|alpha_numeric_spaces|max_length[255]');
        $this->form_validation->set_rules('permission_order', $this->lang->line('permission_order'), 'trim|required|integer');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('adminpanel/permissions');
        }

        if (isset($_POST['edit'])) {
            $this->_edit($this->input->post('permission_id'), array('permission_description' => $this->input->post('permission_description'), 'permission_order' => $this->input->post('permission_order')));
        }elseif(isset($_POST['delete'])) {
            $this->_delete($this->input->post('permission_id'));
        }
    }

    /**
     *
     * _edit: change a permission's data
     *
     * @param int $id the id for the permission to be edited
     * @param array $data the data that will be used to update the permission
     *
     */

    private function _edit($id, $data) {
        $result = $this->permissions_model->save($id, $data);

        if ($result == "system") {
            $this->session->set_flashdata('error', $this->lang->line('permission_system_noedit'));
            redirect('adminpanel/permissions');
        }

        $this->session->set_flashdata('success', sprintf($this->lang->line('permission_updated'), $id));
        redirect('adminpanel/permissions');
    }

    /**
     *
     * _delete: completely remove a permission
     *
     * @param int $id the id for the permission to be deleted
     *
     */

    private function _delete($id) {
        $result = $this->permissions_model->delete($id);

        if ($result === "system") {
            $this->session->set_flashdata('error', $this->lang->line('permission_system_nodelete'));
            redirect('adminpanel/permissions');
        }

        $this->session->set_flashdata('success', $this->lang->line('permission_removed'));
        redirect('adminpanel/permissions');
    }

    /**
     *
     * add_permission: create a new permission
     *
     */

    public function add_permission() {
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('permission_description', $this->lang->line('permission_description'), 'trim|required|alpha_numeric_spaces|max_length[255]');
        $this->form_validation->set_rules('permission_order', $this->lang->line('permission_order'), 'trim|required|integer');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('adminpanel/permissions');
            exit();
        }

        if(!$this->permissions_model->create(array('permission_description' => $this->input->post('permission_description'), 'permission_order' => $this->input->post('permission_order')))) {
            $this->session->set_flashdata('error', $this->lang->line('permission_unable_add'));
            redirect('adminpanel/permissions');
        }

        $this->session->set_flashdata('success', sprintf($this->lang->line('permission_created'), $this->input->post('permission_description')));
        redirect('adminpanel/permissions');
    }

}
