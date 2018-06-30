<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_member extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        if (! self::check_permissions(4)) {
            redirect("/adminpanel/no_access");
        }
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index() {

        // get all roles
        $this->load->model('utils/rbac_model');
        $content_data['roles'] = $this->rbac_model->get_roles();

        $this->template->set_js('clipboard', base_url() .'assets/vendor/clipboard/clipboard.min.js');

        $this->template->set_js('big-min', base_url() .'assets/vendor/diceware/components/big.min.js');
        $this->template->set_js('special-min', base_url() .'assets/vendor/diceware/lists/special-min.js');
        $this->template->set_js('diceware-min', base_url() .'assets/vendor/diceware/lists/diceware-min.js');
        $this->template->set_js('eff', base_url() .'assets/vendor/diceware/lists/eff.js');
        $this->template->set_js('password-gen', base_url() .'assets/vendor/diceware/password_generator.js');

        $this->template->set_metadata('description', $this->lang->line('add_member_meta_description'));

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('add_member'),
            'add_member',
            $this->_header,
            $this->_footer,
            '',
            $content_data
        );
    }

    /**
     *
     * add: add member from post data.
     *
     */

    public function add() {

        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('first_name', $this->lang->line('add_member_first_name'), 'trim|required|max_length[40]|min_length[2]');
        $this->form_validation->set_rules('last_name', $this->lang->line('add_member_last_name'), 'trim|required|max_length[60]|min_length[2]');
        $this->form_validation->set_rules('email', $this->lang->line('add_member_email_address'), 'trim|required|max_length[254]|is_valid_email|is_db_cell_available[user.email]');

        if (!$this->input->post('username_from_email')) {
            $this->form_validation->set_rules('username', $this->lang->line('add_member_username'), 'trim|required|max_length[24]|min_length[6]|is_valid_username|is_db_cell_available[user.username]');
        }
        $this->form_validation->set_rules('password', $this->lang->line('add_member_password'), 'trim|required|max_length[255]|min_length[9]|is_valid_password');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('add_member_password_confirm'), 'trim|required|max_length[255]|min_length[9]|matches[password]');
        $this->form_validation->set_rules('username_from_email', $this->lang->line('add_member_username_from_email'), 'trim|alpha');
        $this->form_validation->set_rules('inform_member', $this->lang->line('add_member_inform_member'), 'trim|alpha');

        if (isset($_POST['roles'])) {
            foreach ($_POST['roles'] as $role) {
                $role = trim($role);
                if( ! $this->form_validation->is_natural_no_zero($role)) {
                    $this->session->set_flashdata('error', $this->lang->line('illegal_input'));
                    redirect('adminpanel/add_member');
                }
            }
        }

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            $this->session->set_flashdata($_POST);
            redirect('/adminpanel/add_member');
        }

        $new_username = $this->input->post('username');
        if ($this->input->post('username_from_email')) {
            $new_username = str_replace("@", "-", $this->input->post('email'));
        }

        // create directory
        if (!file_exists(FCPATH .'assets/img/members/'. $new_username)) {
            mkdir(FCPATH .'assets/img/members/'. $new_username);
        }else{
            $this->session->set_flashdata('error', $this->lang->line('create_imgfolder_failed'));
            redirect('adminpanel/add_member');
        }

        // load membership model
        $this->load->model('auth/register_model');
        if($return_array = $this->register_model->create_member($new_username, $this->input->post('password'), $this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'))) {

            // set roles
            $this->load->model('utils/rbac_model');
            if (isset($_POST['roles'])) {
                foreach($_POST['roles'] as $role) {
                    $this->rbac_model->create_user_role(array('user_id' => $return_array['user_id'], 'role_id' => $role));
                }
            }
            // add default member role
            $this->rbac_model->create_user_role(array('user_id' => $return_array['user_id'], 'role_id' => 4));

            // send confirmation email(s)
            $this->load->helper('send_email');
            $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
            $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
            $this->email->to($this->input->post('email'));
            $this->email->set_mailtype("html");

            // admin activation is not required but we checked the box to inform the member
            if ($this->input->post('inform_member') && !Settings_model::$db_config['registration_activation_required']) {

                $this->email->subject($this->lang->line('add_member_email_active_subject'));

                $this->email->message(
                    $this->load->view('generic/email_templates/header.php', array('username' => $new_username), true) .
                    $this->load->view('themes/adminpanel/email_templates/add-member.php', '', true) .
                    $this->load->view('generic/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );
                $this->email->set_alt_message(
                    $this->load->view('generic/email_templates/header-txt.php', array('username' => $new_username), true) .
                    $this->load->view('themes/adminpanel/email_templates/add-member-txt.php', '', true) .
                    $this->load->view('generic/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );

                $this->email->send();
                $this->session->set_flashdata('success', $this->lang->line('add_member_created'));
            }

            // activation is required: always send an email in this case
            if (Settings_model::$db_config['registration_activation_required']) {
                $this->email->subject($this->lang->line('add_member_email_subject'));

                $this->email->message(
                    $this->load->view('generic/email_templates/header.php', array('username' => $new_username), true) .
                    $this->load->view('themes/adminpanel/email_templates/add-member-activation.php', array('cookie_part' => $return_array['cookie_part']), true) .
                    $this->load->view('generic/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );
                $this->email->set_alt_message(
                    $this->load->view('generic/email_templates/header-txt.php', array('username' => $new_username), true) .
                    $this->load->view('themes/adminpanel/email_templates/add-member-activation-txt.php', array('cookie_part' => $return_array['cookie_part']), true) .
                    $this->load->view('generic/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );

                $this->email->send();
                $this->session->set_flashdata('success', $this->lang->line('add_member_created'));
            }

            // note: approval is not required as we are creating the account manually anyway

            redirect('/adminpanel/member_detail/'. $return_array['user_id']);

        }else{
            $this->session->set_flashdata('error', $this->lang->line('add_member_unable'));
            redirect('/adminpanel/add_member');
        }
    }

}