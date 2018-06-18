<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_settings extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('encryption');
        $this->load->library('form_validation');
        $this->lang->load('site_settings');
        $this->load->model('adminpanel/site_settings_model');
    }

    public function index() {

        if (! self::check_permissions(3)) {
            redirect("/adminpanel/no_access");
        }

        $content_data['private_pages'] = $this->_load_membership_pages();

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('site_settings_title'),
            'site_settings',
            $this->_header,
            $this->_footer,
            '',
            $content_data);
    }

    /**
     *
     * _load_membership_pages: loads all the pages from the membership module to make them selectable in the site settings page
     *
     */

    private function _load_membership_pages() {
        if ($handle = opendir(APPPATH. 'modules/membership/controllers')) {
            $pages = array();
            while (false !== ($file = readdir($handle))) {
                $last_four = substr($file, -4);
                $newfile = str_replace(".php", "", $file);
                if ($last_four == ".php") {
                    $pages[$newfile] = strtolower(str_replace("_", " ", $newfile));
                }
            }

            closedir($handle);

            return $pages;
        }

        return false;
    }


    public function save_general() {

        if (! self::check_permissions(11)) {
            redirect('/adminpanel/site_settings');
        }

        $this->form_validation->set_rules('site_title', $this->lang->line('site_title'), 'trim|required|max_length[60]');
        $this->form_validation->set_rules('admin_email', $this->lang->line('admin_email'), 'trim|required|max_length[254]|is_valid_email');
        $this->form_validation->set_rules('admin_ip_address', $this->lang->line('admin_ip_address'), 'trim|required|strip_tags');
        $this->form_validation->set_rules('site_disabled_text', $this->lang->line('disabled_text'), 'trim');
        $this->form_validation->set_rules('members_per_page', $this->lang->line('members_per_page'), 'trim|required|numeric');
        $this->form_validation->set_rules('active_theme', $this->lang->line('active_theme'), 'trim|required|max_length[40]');
        $this->form_validation->set_rules('adminpanel_theme', $this->lang->line('adminpanel_theme'), 'trim|required|max_length[40]');
        $this->form_validation->set_rules('google_analytics_tracking_code', $this->lang->line('google_analytics_tracking_code'), 'trim|max_length[20]');
        $this->form_validation->set_rules('cookie_expires', $this->lang->line('cookie_expiration'), 'trim|required|numeric');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/site_settings');
        }

        // check admin_ip_address entry
        $this->load->helper('ip');
        if (!check_ip($this->input->post('admin_ip_address'))) {
            $this->session->set_flashdata('error', $this->lang->line('admin_ip_address_error'));
            redirect('/adminpanel/site_settings');
        }

        // set theming
        $active_theme = false;
        $list = glob(APPPATH .'views/themes/'. $this->input->post('active_theme') .'/layouts/*.php');
        if (!empty($list)) {
            $active_theme = true;
        }else{
            $this->session->set_flashdata('error', sprintf($this->lang->line('main_not_found'), $this->input->post('active_theme')));
            redirect('/adminpanel/site_settings');
            exit();
        }

        $adminpanel_theme = false;
        if (file_exists(APPPATH .'views/themes/'. $this->input->post('adminpanel_theme') .'/layouts/adminpanel.php')) {
            $adminpanel_theme = true;
        }else{
            $this->session->set_flashdata('error', sprintf($this->lang->line('main_not_found'), $this->input->post('adminpanel_theme')));
            redirect('/adminpanel/site_settings');
            exit();
        }

        $data = array(
            'site_title' => $this->input->post('site_title'),
            'admin_email' => $this->input->post('admin_email'),
            'disable_all' => ($this->input->post('disable_all') == "" ? 0 : 1),
            'admin_ip_address' => $this->input->post('admin_ip_address'),
            'site_disabled_text' => $this->input->post('site_disabled_text'),
            'members_per_page' => ($this->input->post('members_per_page') > 0 ? $this->input->post('members_per_page') : 10),
            'active_theme' => ($active_theme == TRUE ? $this->input->post('active_theme') : Settings_model::$db_config['active_theme']),
            'adminpanel_theme' => ($adminpanel_theme == TRUE ? $this->input->post('adminpanel_theme') : Settings_model::$db_config['adminpanel_theme']),
            'google_analytics_tracking_code' => $this->input->post('google_analytics_tracking_code'),
            'cookie_expires' => $this->input->post('cookie_expires')
        );

        if (!$this->site_settings_model->save_settings($data)) {
            $this->session->set_flashdata('error', $this->lang->line('settings_update_error'));
        }else{
            $this->session->set_flashdata('success', $this->lang->line('settings_update'));
            $this->load->library('cache');
            $this->cache->delete('settings');
        }

        redirect('/adminpanel/site_settings');
    }

    public function save_login() {

        if (!self::check_permissions(11)) {
            redirect('/adminpanel/site_settings');
        }

        $this->form_validation->set_rules('allow_login_by_email', $this->lang->line('allow_login_by_email'), 'trim|alpha');
        $this->form_validation->set_rules('home_page', $this->lang->line('post_login_page'), 'trim|required|max_length[50]');
        $this->form_validation->set_rules('password_link_expires', $this->lang->line('password_link_expiration'), 'trim|required|numeric');
        $this->form_validation->set_rules('max_login_attempts', $this->lang->line('max_login_attempts'), 'trim|required|numeric');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/site_settings');
        }

        // set theming
        $home_page = FALSE;
        if (file_exists(APPPATH .'modules/membership/controllers/'. $this->input->post('home_page') .'.php')) {
            $home_page = TRUE;
        }else{
            $this->session->set_flashdata('error', sprintf($this->lang->line('controller_not_found'), $this->input->post('home_page')));
            redirect('/adminpanel/site_settings');
            exit();
        }

        $data = array(
            'login_enabled' => ($this->input->post('login_enabled') == "" ? 1 : 0),
            'remember_me_enabled' => ($this->input->post('remember_me_enabled') != "" ? true : false),
            'allow_login_by_email' => ($this->input->post('allow_login_by_email') != "" ? true : false),
            'previous_url_after_login' => ($this->input->post('previous_url_after_login') == "" ? 0 : 1),
            'home_page' => ($home_page == TRUE ? $this->input->post('home_page') : strtolower(Settings_model::$db_config['home_page'])),
            'password_link_expires' => $this->input->post('password_link_expires'),
            'max_login_attempts' => $this->input->post('max_login_attempts')
        );

        if (!$this->site_settings_model->save_settings($data)) {
            $this->session->set_flashdata('error', $this->lang->line('settings_update_error'));
        }else{
            $this->session->set_flashdata('success', $this->lang->line('settings_update'));
            $this->load->library('cache');
            $this->cache->delete('settings');
        }

        redirect('/adminpanel/site_settings');

    }

    public function save_register() {

        if (!self::check_permissions(11)) {
            redirect('/adminpanel/site_settings');
        }

        $this->form_validation->set_rules('registration_requires_password', $this->lang->line('registration_requires_password'), 'trim|alpha');
        $this->form_validation->set_rules('registration_requires_username', $this->lang->line('registration_requires_username'), 'trim|alpha');
        $this->form_validation->set_rules('registration_activation_required', $this->lang->line('registration_activation_required'), 'trim|alpha');
        $this->form_validation->set_rules('registration_approval_required', $this->lang->line('registration_approval_required'), 'trim|alpha');
        $this->form_validation->set_rules('activation_link_expires', $this->lang->line('activation_link_expiration'), 'trim|required|numeric');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/site_settings');
        }

        $data = array(
            'register_enabled' => ($this->input->post('register_enabled') == "" ? 1 : 0),
            'registration_requires_password' => ($this->input->post('registration_requires_password') != "" ? true : false),
            'registration_requires_username' => ($this->input->post('registration_requires_username') != "" ? true : false),
            'registration_activation_required' => ($this->input->post('registration_activation_required') != "" ? true : false),
            'registration_approval_required' => ($this->input->post('registration_approval_required') != "" ? true : false),
            'activation_link_expires' => $this->input->post('activation_link_expires')
        );

        if (!$this->site_settings_model->save_settings($data)) {
            $this->session->set_flashdata('error', $this->lang->line('settings_update_error'));
        }else{
            $this->session->set_flashdata('success', $this->lang->line('settings_update'));
            $this->load->library('cache');
            $this->cache->delete('settings');
        }

        redirect('/adminpanel/site_settings');

    }

    public function save_oauth() {

        if (!self::check_permissions(11)) {
            redirect('/adminpanel/site_settings');
        }

        $this->form_validation->set_rules('oauth_requires_username', $this->lang->line('oauth_requires_username'), 'trim|alpha');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/site_settings');
        }

        $data = array(
            'oauth_enabled' => ($this->input->post('oauth_enabled') == "" ? 0 : 1),
            'oauth_requires_username' => ($this->input->post('oauth_requires_username') != "" ? true : false)
        );

        if (!$this->site_settings_model->save_settings($data)) {
            $this->session->set_flashdata('error', $this->lang->line('settings_update_error'));
        }else{
            $this->session->set_flashdata('success', $this->lang->line('settings_update'));
            $this->load->library('cache');
            $this->cache->delete('settings');
        }

        redirect('/adminpanel/site_settings');

    }

    public function save_members() {

        if (!self::check_permissions(11)) {
            redirect('/adminpanel/site_settings');
        }

        $this->form_validation->set_rules('allow_username_change', $this->lang->line('allow_username_change'), 'trim|alpha');
        $this->form_validation->set_rules('change_password_send_email', $this->lang->line('change_password_send_email'), 'trim|alpha');
        $this->form_validation->set_rules('picture_max_upload_size', $this->lang->line('picture_max_upload_size'), 'trim|numeric|max_length[10]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/site_settings');
        }

        $data = array(
            'allow_username_change' => ($this->input->post('allow_username_change') != "" ? true : false),
            'change_password_send_email' => ($this->input->post('change_password_send_email') != "" ? true : false),
            'picture_max_upload_size' => $this->input->post('picture_max_upload_size'),
        );

        if (!$this->site_settings_model->save_settings($data)) {
            $this->session->set_flashdata('error', $this->lang->line('settings_update_error'));
        }else{
            $this->session->set_flashdata('success', $this->lang->line('settings_update'));
            $this->load->library('cache');
            $this->cache->delete('settings');
        }

        redirect('/adminpanel/site_settings');
    }

    public function save_mail() {

        if (!self::check_permissions(11)) {
            redirect('/adminpanel/site_settings');
        }

        $this->form_validation->set_rules('sendmail_path', $this->lang->line('sendmail_path'), 'trim');
        $this->form_validation->set_rules('email_protocol', $this->lang->line('sendmail_path'), 'trim');
        $this->form_validation->set_rules('smtp_host', $this->lang->line('smtp_host'), 'trim');
        $this->form_validation->set_rules('smtp_port', $this->lang->line('smtp_port'), 'trim');
        $this->form_validation->set_rules('smtp_user', $this->lang->line('smtp_user'), 'trim');
        $this->form_validation->set_rules('smtp_password', $this->lang->line('smtp_password'), 'trim');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/site_settings');
        }

        $data = array(
            'allow_username_change' => ($this->input->post('allow_username_change') != "" ? true : false),
            'change_password_send_email' => ($this->input->post('change_password_send_email') != "" ? true : false),
            'email_protocol' => $this->input->post('email_protocol'),
            'sendmail_path' => $this->input->post('sendmail_path'),
            'smtp_host' => $this->input->post('smtp_host'),
            'smtp_port' => $this->input->post('smtp_port'),
            'smtp_user' => $this->encryption->encrypt($this->input->post('smtp_user')),
            'smtp_pass' => $this->encryption->encrypt($this->input->post('smtp_pass'))
        );

        if (!$this->site_settings_model->save_settings($data)) {
            $this->session->set_flashdata('error', $this->lang->line('settings_update_error'));
        }else{
            $this->session->set_flashdata('success', $this->lang->line('settings_update'));
            $this->load->library('cache');
            $this->cache->delete('settings');
        }

        redirect('/adminpanel/site_settings');
    }

    public function save_recaptcha() {

        if (!self::check_permissions(11)) {
            redirect('/adminpanel/site_settings');
        }

        $this->form_validation->set_rules('recaptchav2_site_key', $this->lang->line('site_key'), 'trim|max_length[40]');
        $this->form_validation->set_rules('recaptchav2_secret', $this->lang->line('site_secret'), 'trim|max_length[40]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/site_settings');
        }

        $data = array(
            'recaptchav2_enabled' => ($this->input->post('recaptchav2_enabled') != "" ? true : false),
            'recaptchav2_site_key' => $this->input->post('recaptchav2_site_key'),
            'recaptchav2_secret' => $this->input->post('recaptchav2_secret'),
            'login_attempts' => $this->input->post('login_attempts')
        );

        if (!$this->site_settings_model->save_settings($data)) {
            $this->session->set_flashdata('error', $this->lang->line('settings_update_error'));
        }else{
            $this->session->set_flashdata('success', $this->lang->line('settings_update'));
            $this->load->library('cache');
            $this->cache->delete('settings');
        }

        redirect('/adminpanel/site_settings');
    }


    /**
     *
     * clear_sessions: force relogins for all users by clearing the sessions table (except for the main admin account)
     *
     */

    public function clear_sessions() {

        if (! self::check_permissions(12)) {
            redirect('/adminpanel/site_settings');
        }

        if ($this->site_settings_model->clear_sessions()) {
            $this->session->set_flashdata('sessions_message', $this->lang->line('sessions_cleared'));
        }else{
            $this->session->set_flashdata('sessions_message', $this->lang->line('sessions_not_cleared'));
        }

        redirect('/adminpanel/site_settings#clear_sessions');
    }

}