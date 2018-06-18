<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->lang->load('membership');
        $this->load->model('profile_model');
    }

    public function index() {

        // set content data
        $content_data = $this->profile_model->get_profile();

        $this->template->set_js('widget', base_url() .'assets/vendor/jquery/jquery.ui.widget.js');
        $this->template->set_js('upload', base_url() .'assets/vendor/fileupload/jquery.fileupload.js');

        $this->template->set_js('clipboard', base_url() .'assets/vendor/clipboard/clipboard.min.js');

        $this->template->set_js('big-min', base_url() .'assets/vendor/diceware/components/big.min.js');
        $this->template->set_js('special-min', base_url() .'assets/vendor/diceware/lists/special-min.js');
        $this->template->set_js('diceware-min', base_url() .'assets/vendor/diceware/lists/diceware-min.js');
        $this->template->set_js('eff', base_url() .'assets/vendor/diceware/lists/eff.js');
        $this->template->set_js('password-gen', base_url() .'assets/vendor/diceware/password_generator.js');

        if ($glob = glob(FCPATH .'assets/img/members/'. $this->session->userdata('username') .'/*.{jpg,jpeg,png}', GLOB_BRACE)) {
            $content_data->profile_image = basename($glob[0]);
        }

        $content_data->picture_max_upload_size = Settings_model::$db_config['picture_max_upload_size'];

        $this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel', $this->lang->line('profile_title'), 'profile', 'header', 'footer', '', $content_data);
    }

    /**
     *
     * update_account: change member info

     */

    public function update_account() {
        // form input validation
        if ($this->input->post('user_id') != strval(intval($this->input->post('user_id')))) {
            redirect('private/profile');
        }

        $this->form_validation->set_error_delimiters('<p>', '</p>');

        if (Settings_model::$db_config['allow_username_change'] && ($this->input->post('username') != $this->session->userdata('username'))) {
            $this->form_validation->set_rules('username', $this->lang->line('profile_username'), 'trim|required|max_length[24]|min_length[6]|is_valid_username|is_db_cell_available[user.username]');
        }

        $this->form_validation->set_rules('first_name', $this->lang->line('profile_first_name'), 'trim|required|max_length[40]|min_length[2]');
        $this->form_validation->set_rules('last_name', $this->lang->line('profile_last_name'), 'trim|required|max_length[60]|min_length[2]');
        $this->form_validation->set_rules('email', $this->lang->line('profile_email_address'), 'trim|max_length[254]|is_valid_email|is_db_cell_available_by_id[user.email.'. $this->input->post('user_id') .'.user_id]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('membership/profile');
            exit();
        }

        // prep insert data
        $data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email')
        );

        if (Settings_model::$db_config['allow_username_change'] && $this->input->post('username') != "") {
            $data['username'] = $this->input->post('username');
        }else{
            $data['username'] = $this->session->userdata('username');
        }

        // update profile
        $result = $this->profile_model->set_profile($data);
        if (isset($result['newemail'])) {
            // send email
            $this->load->helper('send_email');
            $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
            $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
            $this->email->to($this->input->post('email'));
            $this->email->set_mailtype("html");

            $this->email->subject($this->lang->line('profile_email_reactivation_subject'));

            $data['email'] = $this->input->post('email');
            $data['cookie_part'] = $result['cookie_part'];

            $this->email->message(
                $this->load->view('generic/email_templates/header.php', array('new_username' => $data['username']), true) .
                $this->load->view('themes/adminpanel/email_templates/profile.php', $data, true) .
                $this->load->view('generic/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
            );
            $this->email->set_alt_message(
                $this->load->view('generic/email_templates/header-txt.php', array('new_username' => $data['username']), true) .
                $this->load->view('themes/adminpanel/email_templates/profile-txt.php', $data, true) .
                $this->load->view('generic/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
            );

            $this->email->send();
        }

        // check root admin username cache and update it with new value if matched with current session data
        if ($this->session->userdata('username') == Settings_model::$db_config['root_admin_username']) {
            if (!Settings_model::update_config_value('root_admin_username', $this->input->post('username'))) {
                $this->session->set_flashdata('success', 'Unable to update root admin user. Please rename img/members/'. Settings_model::$db_config['root_admin_username'] .' folder manually!');
            }
            // reload cache
            $this->load->library('cache');
            $this->cache->delete('settings');
            Settings_model::load_settings();
        }

        // rename username folder
        if (!file_exists(FCPATH .'assets/img/members/'. $this->input->post('username')) &&
            file_exists(FCPATH .'assets/img/members/'. $this->session->userdata('username'))) {
            rename(FCPATH .'assets/img/members/'. $this->session->userdata('username'), FCPATH .'assets/img/members/'. $this->input->post('username'));
        }

        // update session AFTER checks above in cache AND renaming of folder
        if (Settings_model::$db_config['allow_username_change'] && $this->input->post('username') != "") {
            $this->session->set_userdata('username', $this->input->post('username'));
        }

        $this->session->set_flashdata('success', $this->lang->line('profile_updated'));
        redirect('membership/profile');
        exit();
    }

    /**
     *
     * update_password: change member password
     *
     */

    public function update_password() {
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('current_password', $this->lang->line('profile_current_password'), 'trim|required|max_length[255]|is_member_password');
        $this->form_validation->set_rules('password', $this->lang->line('profile_new_password'), 'trim|required|max_length[255]|min_length[9]|is_valid_password');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('profile_new_password_repeat'), 'trim|required|max_length[255]|min_length[9]|matches[password]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('pwd_error', validation_errors());
            redirect('membership/profile#profile_pwd_form');
            exit();
        }

        $this->load->helper('send_email');
        $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
        $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
        $this->email->to($this->input->post('email'));
        $this->email->set_mailtype("html");

        if ($this->profile_model->set_password($this->input->post('password'))) {

            if ($this->input->post('send_copy') != "") {

                $this->email->subject($this->lang->line('profile_email_password_subject'));

                $this->email->message(
                    $this->load->view('generic/email_templates/header.php', array('new_username' => $this->session->userdata('username')), true) .
                    $this->load->view('themes/adminpanel/email_templates/profile-password.php', '', true) .
                    $this->load->view('generic/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );
                $this->email->set_alt_message(
                    $this->load->view('generic/email_templates/header-txt.php', array('new_username' => $this->session->userdata('username')), true) .
                    $this->load->view('themes/adminpanel/email_templates/profile-password-txt.php', '', true) .
                    $this->load->view('generic/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );

                $this->email->send();

            }elseif (Settings_model::$db_config['change_password_send_email']) {

                $this->email->subject($this->lang->line('profile_email_password_subject'));

                $this->email->message(
                    $this->load->view('themes/adminpanel/email_templates/header.php', array('new_username' => $this->session->userdata('username')), true) .
                    $this->load->view('themes/adminpanel/email_templates/profile-password.php', '', true) .
                    $this->load->view('themes/adminpanel/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );
                $this->email->set_alt_message(
                    $this->load->view('themes/adminpanel/email_templates/header-txt.php', array('new_username' => $this->session->userdata('username')), true) .
                    $this->load->view('themes/adminpanel/email_templates/profile-password-txt.php', '', true) .
                    $this->load->view('themes/adminpanel/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
                );

                $this->email->send();
            }
            $this->session->set_flashdata('pwd_success', $this->lang->line('profile_password_change_success'));
        }
        redirect('membership/profile');
    }

    /**
     *
     * delete_account: change member password
     *
     */
	
	public function delete_account() {
		if ($this->session->userdata('username') == Settings_model::$db_config['root_admin_username']) {
			$this->session->set_flashdata('error', $this->lang->line('profile_admin_nodelete'));
			redirect('membership/profile');
		}

		if ($this->profile_model->delete_membership()) {

            // delete img folders
            $path = FCPATH .'assets/img/members/'. $this->session->userdata('username');
            $this->load->helper("file"); // load the helper
            delete_files($path, true); // delete all files/folders
            rmdir($path); // remove member folder

			redirect("logout"); // logout controller destroys session and cookies
		}
		$this->session->set_flashdata('error', $this->lang->line('profile_remove_error'));
		redirect('membership/profile');
	}

    /**
     *
     * upload_profile_picture
     *
     */

    public function upload_profile_picture($username) {

        $this->load->library('upload_member_profile_picture');

        if ($this->input->is_ajax_request()) {

            // validation
            if (!$this->form_validation->is_valid_username($username)) {
                exit(); // silent death, the username was possibly forged, falsified
            }

            $upload_data = $this->upload_member_profile_picture->upload($username);

            // update user profile_img
            $this->profile_model->update_profile_img($upload_data['new_name']);

            // update session profile_img
            $this->session->set_userdata('profile_img', $upload_data['new_name']);

            echo json_encode($upload_data);
        }else{
            echo false;
        }
    }

    /**
     *
     * delete_profile_picture
     *
     */

    public function delete_profile_picture() {

        $this->load->library('upload_member_profile_picture');

        if ($this->upload_member_profile_picture->delete($this->session->userdata('username'))) {
            $this->session->set_flashdata('success', $this->lang->line('profile_picture_deleted'));
            // reset user profile_img
            $this->profile_model->delete_profile_img();
            $this->session->set_userdata('profile_img', MEMBERS_GENERIC);
        }else{
            $this->session->set_flashdata('error', $this->lang->line('nothing_deleted'));
        }

        redirect('membership/profile');
    }

}