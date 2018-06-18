<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_members extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('adminpanel/list_members_model');
    }

    /**
     *
     * index: main function with search and pagination built into it
     *
     * @param int|string $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     * @param int $offset the offset to be used for selecting data
     */

    public function index($order_by = "username", $sort_order = "asc", $search = "all", $offset = 0) {

        if (! self::check_permissions(1)) {
            redirect("/adminpanel/no_access");
        }

        if (!is_numeric($offset)) {
            redirect('/adminpanel/list_members');
        }

        // validation
        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_username' => '', 's_first_name' => '', 's_last_name' => '', 's_email' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('username', $this->lang->line('list_members_username'), 'trim|max_length[24]');
            $this->form_validation->set_rules('first_name', $this->lang->line('list_members_first_name'), 'trim|max_length[40]');
            $this->form_validation->set_rules('last_name', $this->lang->line('list_members_last_name'), 'trim|max_length[60]');
            $this->form_validation->set_rules('email', $this->lang->line('list_members_email_address'), 'trim|max_length[254]');

            if (empty($_POST['username']) && empty($_POST['first_name']) && empty($_POST['last_name']) && empty($_POST['email'])) {
                $this->session->set_flashdata('error', $this->lang->line('list_members_empty_search'));
                redirect('/adminpanel/list_members/');
                exit();
            }elseif (!$this->form_validation->run()) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('/adminpanel/list_members/');
                exit();
            }

            $search_session = array(
                's_username'  => $this->input->post('username'),
                's_first_name'     => $this->input->post('first_name'),
                's_last_name' => $this->input->post('last_name'),
                's_email' => $this->input->post('email')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('username' => $this->input->post('username'), 'first_name' => $this->input->post('first_name'), 'last_name' => $this->input->post('last_name'), 'email' => $this->input->post('email'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_members_model->count_all_search_members($search_data);
            $content_data['search'] = "session";

        }elseif($search == "session") {
            // search result is found in session: paginate accordingly (keep only the search results in the list)
            $base_url = site_url('adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('username' => $this->session->userdata('s_username'), 'first_name' => $this->session->userdata('s_first_name'), 'last_name' => $this->session->userdata('s_last_name'), 'email' => $this->session->userdata('s_email'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_members_model->count_all_search_members($search_data);
            $content_data['search'] = "session";

        }else{
            // regular listing, no search done or found in session
            $unset_search_session = array('s_username' => '', 's_first_name' => '', 's_last_name' => '', 's_email' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_members_model->count_all_members();
            $base_url = site_url('adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_members_model->get_members($per_page, $offset, $order_by, $sort_order, $search_data);

        if (!empty($data)) {
            $content_data['members'] = $data;
        }

        $content_data['offset'] = $offset;
        $content_data['order_by'] = $order_by;
        $content_data['sort_order'] = $sort_order;

        // set pagination config data
        $config['uri_segment'] = '7';
        $config['base_url'] = $base_url;
        $config['per_page'] = Settings_model::$db_config['members_per_page'];
        $config['prev_tag_open'] = ''; // removes &nbsp; at beginning of pagination output
        $config['full_tag_open'] = '<div><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = '<li>';
        $config['num_tag_close'] = $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="javascript:">';
        $config['cur_tag_close'] = '</a></li>';
        $this->pagination->initialize($config);

        // set layout data
        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('list_members_title'),
            'list_members',
            $this->_header,
            $this->_footer,
            '',
            $content_data
        );
    }

    /**
     *
     * toggle_ban: (un)ban member from adminpanel
     *
     * @param int $id the id of the member to be banned
     * @param string $username the username of the member involved
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     * @param bool $banned ban or unban?
     *
     */

    public function toggle_ban($id, $username, $offset, $order_by, $sort_order, $search, $banned) {

        if (! self::check_permissions(9)) {
            $this->session->set_flasdata('error', $this->lang->line('no_permissions'));
            redirect("/adminpanel/list_members");
        }

        if ($this->list_members_model->get_username_by_id($id) == Settings_model::$db_config['root_admin_username']) {
            $this->session->set_flashdata('error', $this->lang->line('list_members_admin_noban'));
            redirect('/adminpanel/list_members/index');
            return;
        }elseif ($this->list_members_model->toggle_ban($id, $banned)) {
            $banned ? $banned = $this->lang->line('unbanned') : $banned = $this->lang->line('banned');
            $this->session->set_flashdata('success', '<p>'. sprintf($this->lang->line('list_members_toggle_ban'), $username) . $banned .'.</p>');
        }

        redirect('/adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    /**
     *
     * toggle_active: (de)activate member from adminpanel
     *
     * @param int $id the id of the member to be activated
     * @param string $username the username of the member involved
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     * @param bool $active or deactivate?
     *
     */

    public function toggle_active($id, $username, $offset, $order_by, $sort_order, $search, $active) {

        if (! self::check_permissions(10)) {
            $this->session->set_flasdata('error', $this->lang->line('no_permissions'));
            redirect("/adminpanel/list_members");
        }

        if ($this->list_members_model->get_username_by_id($id) == Settings_model::$db_config['root_admin_username']) {
            $this->session->set_flashdata('error', $this->lang->line('list_members_admin_nodeactivate'));
            redirect('/adminpanel/list_members/index');
        }elseif ($this->list_members_model->toggle_active($id, $active)) {
            $active ? $active = $this->lang->line('deactivated') : $active = $this->lang->line('activated');
            $this->session->set_flashdata('success', '<p>'. sprintf($this->lang->line('list_members_toggle_active'), $username) . $active .'.</p>');
        }

        redirect('/adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    public function toggle_approval($id, $username, $offset, $order_by, $sort_order, $search, $approved) {

        if (! Settings_model::$db_config['registration_approval_required']) {
            redirect('adminpanel/list_members');
        }

        if (! self::check_permissions(16)) {
            $this->session->set_flasdata('error', $this->lang->line('no_permissions'));
            redirect("/adminpanel/list_members");
        }

        if ($this->list_members_model->get_username_by_id($id) == Settings_model::$db_config['root_admin_username']) {
            $this->session->set_flashdata('error', $this->lang->line('list_members_admin_nounapprove'));
            redirect('/adminpanel/list_members/index');
        }elseif ($this->list_members_model->toggle_approval($id, $approved)) {
            $approved ? $approved = $this->lang->line('unapproved') : $approved = $this->lang->line('approved');
            $this->session->set_flashdata('success', '<p>'. sprintf($this->lang->line('list_members_toggle_active'), $username) . $approved .'.</p>');
        }

        redirect('/adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    /**
     *
     * mass_action: takes care of the checkbox selection functionality
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    public function mass_action($offset, $order_by, $sort_order, $search) {

        if (!isset($_POST['mass']) || empty($_POST['mass'])) {
            $this->session->set_flashdata('error', $this->lang->line('list_members_nothing_selected'));
            redirect('adminpanel/list_members');
        }

        foreach ($_POST['mass'] as $value) {
            $value = trim($value);
            if( ! $this->form_validation->is_natural_no_zero($value)) {
                $this->session->set_flashdata('error', $this->lang->line('illegal_input'));
                redirect('adminpanel/list_members');
            }
        }

        if ($_POST['mass_action'] == 'delete') {
            $this->_delete($offset, $order_by, $sort_order, $search);
        }elseif ($_POST['mass_action'] == 'activate' || $_POST['mass_action'] == 'deactivate') {
            $this->_mass_activation($offset, $order_by, $sort_order, $search, $_POST['mass_action']);
        }elseif ($_POST['mass_action'] == 'ban' || $_POST['mass_action'] == 'unban') {
            $this->_mass_banning($offset, $order_by, $sort_order, $search, $_POST['mass_action']);
        }elseif ($_POST['mass_action'] == 'approved' || $_POST['mass_action'] == 'unapproved') {
            $this->_mass_approval($offset, $order_by, $sort_order, $search, $_POST['mass_action']);
        }
    }

    /**
     *
     * _delete: deletion of selected members
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _delete($offset, $order_by, $sort_order, $search) {

        if (! self::check_permissions(6)) {
            $this->session->set_flasdata('error', $this->lang->line('no_permissions'));
            redirect("/adminpanel/list_members");
        }

        // loop through the given ids
        foreach($_POST['mass'] as $id) {

            // select username from id
            if ($username = $this->list_members_model->get_username_by_id($id)) {

                if ($username == Settings_model::$db_config['root_admin_username']) {
                    $this->session->set_flashdata('error', $this->lang->line('root_admin_nodelete'));
                    redirect('/adminpanel/list_members');
                }

                // grab the files that need to be purged
                $files = glob(FCPATH .'assets/img/members/'. $username .'/*');

                // loop through files
                if ($files) {
                    foreach($files as $file){
                        if(is_file($file)) {
                            // purge if exists, send error on failure
                            if(!unlink($file)) {
                                $this->session->set_flashdata('error', '<p>'. sprintf($this->lang->line('list_members_could_not_remove_file'), $id) .'.</p>');
                                redirect('/adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
                            }
                        }
                    }
                }

                // once all files are removed (or none are found) then we remove the empty folder and redirect
                if(rmdir(FCPATH .'assets/img/members/'. $username)) {

                    // remove user from DB
                    if (!$this->list_members_model->delete_member($id)) {
                        $this->session->set_flashdata('error', '<p>'. sprintf($this->lang->line('list_members_deletion_failed'), $id) .'.</p>');
                        redirect('/adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
                    }
                }else{
                    // rmdir() failed error
                    $this->session->set_flashdata('error', '<p>'. sprintf($this->lang->line('list_members_failed_to_remove_member_dir'), $id) .'</p>');
                    redirect('/adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
                }
            }
        }

        $this->session->set_flashdata('success', $this->lang->line('list_members_deleted'));
        redirect('/adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);

    }

    private function _mass_banning($offset, $order_by, $sort_order, $search, $action) {

        if (! self::check_permissions(9)) {
            $this->session->set_flasdata('error', $this->lang->line('no_permissions'));
            redirect("/adminpanel/list_members");
        }

        if (!$this->list_members_model->mass_banning($_POST['mass'], $action)) {
            $this->session->set_flashdata('error', $this->lang->line('list_members_nobody_unbanned'));
        }else{
            $this->session->set_flashdata('success', '<p>'. sprintf(($action == "ban" ? $this->lang->line('list_members_banned') : $this->lang->line('list_members_unbanned')), count($_POST['mass'])) .'</p>');
        }

        redirect('/adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    private function _mass_activation($offset, $order_by, $sort_order, $search, $action) {

        if (! self::check_permissions(10)) {
            $this->session->set_flasdata('error', $this->lang->line('no_permissions'));
            redirect("/adminpanel/list_members");
        }

        if (!$this->list_members_model->mass_activation($_POST['mass'], $action)) {
            $this->session->set_flashdata('error', $this->lang->line('list_members_nobody_deactivated'));
        }else{
            $this->session->set_flashdata('success', '<p>'. sprintf(($action == "activate" ? $this->lang->line('list_members_activated') : $this->lang->line('list_members_deactivated')), count($_POST['mass'])) .'</p>');
        }

        redirect('/adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    private function _mass_approval($offset, $order_by, $sort_order, $search, $action) {

        if (! Settings_model::$db_config['registration_approval_required']) {
            redirect('adminpanel/list_members');
        }

        if (! self::check_permissions(16)) {
            $this->session->set_flasdata('error', $this->lang->line('no_permissions'));
            redirect("/adminpanel/list_members");
        }

        if (!$this->list_members_model->mass_approval($_POST['mass'], $action)) {
            $this->session->set_flashdata('error', $this->lang->line('list_members_nobody_unapproved'));
        }else{
            $this->session->set_flashdata('success', '<p>'. sprintf(($action == "approved" ? $this->lang->line('list_members_approved') : $this->lang->line('list_members_unapproved')), count($_POST['mass'])) .'</p>');
        }

        redirect('/adminpanel/list_members/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
}