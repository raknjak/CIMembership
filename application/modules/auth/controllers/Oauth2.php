<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth2 extends Auth_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index() {
        redirect('/login');
    }

    /**
     *
     * init: prepare OAuth login and redirect to external app if checks pass
     *
     * @param string $provider
     *
     */

    public function init($provider) {

        if (!$this->form_validation->alpha_numeric($provider)) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_illegal_provider_name'));
            redirect('login');
        }

        if (!Settings_model::$db_config['oauth_enabled']) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_social_login_disabled'));
            redirect('login');
        }

        $this->load->model('auth/oauth_model');
        if (!$providerData = $this->oauth_model->get_provider_data($provider)) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_illegal_provider_init'));
            redirect('login');
        }

        if(!$providerData->enabled) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_provider_disabled'));
            redirect('login');
        }

        // decrypt provider data
        $this->load->library('encryption');
        $providerData->client_id = $this->encryption->decrypt($providerData->client_id);
        $providerData->client_secret = $this->encryption->decrypt($providerData->client_secret);

        //var_dump($providerData);die;

        // engage provider
        if ($providerData) {
            require APPPATH . 'vendor/PHPLeague-OAuth2/autoload.php';
            $this->load->library('OAuth2/'. $provider);
            $url = $this->{strtolower($provider)}->loadProviderClass($providerData);
            $_SESSION[strtolower($provider) .'state'] = $this->{strtolower($provider)}->getState();
            redirect($url);
        }else{
            $this->session->set_flashdata('error', $this->lang->line('oauth2_illegal_provider_init'));
            redirect('login');
        }
    }

    /**
     *
     * verify: continue after returning from external app and check incoming data
     *
     * @param string $provider
     *
     */

    public function verify($provider) {

        if (!$this->form_validation->alpha_numeric($provider)) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_illegal_provider_name'));
            redirect('login');
        }

        // check state and cross site forgery mitigation
        if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION[strtolower($provider) .'state'])) {
            unset($_SESSION[strtolower($provider) .'state']);
            $this->session->set_flashdata('error', $this->lang->line('oauth2_invalid_state'));
            redirect('login');
        }

        // only if OAuth is enabled we allow continuing
        if (!Settings_model::$db_config['oauth_enabled']) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_login_disabled'));
            redirect('login');
        }


        $this->load->model('oauth_model');
        if (!$providerData = $this->oauth_model->get_provider_data($provider)) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_illegal_provider_init'));
            redirect('login');
        }

        // no provider found - die
        if (!$providerData) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_no_provider_found'));
            redirect('login');
        }

        // decrypt provider data
        $this->load->library('encryption');
        $providerData->client_id = $this->encryption->decrypt($providerData->client_id);
        $providerData->client_secret = $this->encryption->decrypt($providerData->client_secret);

        // set and get providerObject
        require APPPATH . 'vendor/PHPLeague-OAuth2/autoload.php';
        $this->load->library('OAuth2/'. $provider);
        $this->{strtolower($provider)}->setProvider($providerData);
        $providerObject = $this->{strtolower($provider)}->getProvider();

        // Validate the token and die if not OK
        try {
            // Try to get an access token (using the authorization code grant)
            $token = $providerObject->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);
        } catch (Exception $e) {
            //print $e->getMessage();die;
            $this->session->set_flashdata('error', $this->lang->line('oauth2_invalid_token'));
            redirect('login');
        }

        // Get profile data
        try {
            // Grab user details
            $user = $providerObject->getResourceOwner($token);

        } catch (Exception $e) {

            $this->session->set_flashdata('error', $this->lang->line('oauth2_load_userdata_failed'));
            redirect('login');
        }

        // Check db for existing e-mail
        $email = $user->getEmail();

        if (empty($email)) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_email_not_returned'));
            redirect('login');
        }

        $this->load->model('auth/Login_model');
        $userData = $this->Login_model->validate_login(null, null, false, false, null, true, $email);

        if ($userData) {

            // check site settings
            $this->permissions_roles($userData->user_id);

            // let administrators through, the other roles will be redirected when checks below match
            if (!self::check_roles(1)) {
                if(Settings_model::$db_config['login_enabled'] == 0) {
                    $this->session->set_flashdata('error', $this->lang->line('login_disabled'));
                    redirect('login');
                }
            }

            if ($userData->banned == true) {
                $this->session->set_flashdata('error', $this->lang->line('account_is_banned'));
                redirect('login');
            }elseif (Settings_model::$db_config['registration_approval_required'] == true && $userData->approved == false) {
                $this->session->set_flashdata('error', $this->lang->line('oauth2_login_needs_approval'));
                redirect('login');
            }elseif (Settings_model::$db_config['registration_activation_required'] == true && $userData->active == false) {
                $this->session->set_flashdata('error', $this->lang->line('oauth2_not_active'));
                redirect('login');
            }

            // user exists - set session data and log in
            $this->load->helper('session');
            session_init($userData);

            // create or renew cookie
            $this->load->helper('cookie');
            $cookie_domain = config_item('cookie_domain');

            $cookie = get_cookie('unique_token');

            if ($cookie) {
                // cookie is already set, renew it
                setcookie("unique_token", $cookie, time() + Settings_model::$db_config['cookie_expires'], '/', $cookie_domain, false, false);
            }else{
                // needs new cookie
                setcookie("unique_token", $userData->cookie_part . substr(uniqid(mt_rand(), true), -10) . $userData->cookie_part, time() + Settings_model::$db_config['cookie_expires'], '/', $cookie_domain, false, false);
            }

            // redirect to private section
            redirect('membership/'. strtolower(Settings_model::$db_config['home_page']));

        }else{
            // user does not exist: show username creation form

            // are we allowed to create an account?
            if (Settings_model::$db_config['register_enabled'] == 0) {
                $this->session->set_flashdata('error', $this->lang->line('registration_disabled'));
                redirect('register');
            }

            $this->session->set_flashdata('provider', $provider);
            $this->session->set_flashdata('email', $email);

            $content_data['email'] = $email;

            $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('oauth2_add_username'), 'oauth2_user', 'header', 'footer', '', $content_data);
        }
    }

    /**
     *
     * finalize: OAuth login is successful: finalize by creating account
     *
     */

    public function finalize() {

        // check site settings
        if (Settings_model::$db_config['register_enabled'] == 0) {
            $this->session->set_flashdata('error', $this->lang->line('registration_disabled'));
            redirect('register');
        }elseif (! Settings_model::$db_config['login_enabled']) {
            $this->session->set_flashdata('error', $this->lang->line('login_disabled'));
            redirect('login');
        }elseif (! Settings_model::$db_config['oauth_enabled']) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_login_disabled'));
            redirect('login');
        }

        // set default username
        $oauth2_username = str_replace("@", "-", $this->input->post('email'));

        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');

        if (Settings_model::$db_config['oauth_requires_username']) {
            $oauth2_username = $this->input->post('username'); // overwrite username with user input
            $this->form_validation->set_rules('username', $this->lang->line('oauth2_username'), 'trim|required|max_length[24]|min_length[6]|is_valid_username|is_db_cell_available[user.username]');
        }

        $this->form_validation->set_rules('provider', $this->lang->line('oauth2_provider'), 'trim|required|alpha');
        $this->form_validation->set_rules('email', $this->lang->line('oauth2_email_address'), 'trim|required|max_length[254]|is_valid_email|is_db_cell_available[user.email]');


        // request new Object, need to reinit to get new url and state
        $this->load->model('Oauth_model');
        if (!$providerData = $this->Oauth_model->get_provider_data($this->input->post('provider'))) {
            $this->session->set_flashdata('error', $this->lang->line('oauth2_illegal_provider_init'));
            redirect('login');
        }

        // build the new provider data
        if ($providerData) {
            require APPPATH . 'vendor/PHPLeague-OAuth2/autoload.php';
            $this->load->library('OAuth2/'. $this->input->post('provider'));
            // set the new url
            $newUrl = $this->{strtolower($this->input->post('provider'))}->loadProviderClass($providerData);
            // renew state with updated token data
            $_SESSION[strtolower($this->input->post('provider')) .'state'] = $this->{strtolower($this->input->post('provider'))}->getState();
        }else{
            $this->session->set_flashdata('error', $this->lang->line('oauth2_refresh_token_failed'));
            redirect('login');
        }

        // return form errors
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($newUrl);
        }

        // create member
        if (!$userData = $this->Oauth_model->create_member_oauth(
            $oauth2_username,
            $this->input->post('email')
            )
        ) {
            $this->session->set_flashdata('error', 'oauth2_member_creation_failed');
            redirect('/login');
        }

        // add roles
        $this->load->model('utils/rbac_model');
        if (!$this->rbac_model->create_user_role(array('user_id' => $userData->user_id, 'role_id' => 4)))
        {
            $this->session->set_flashdata('error', 'oauth2_roles_creation_failed');
            redirect('/login');
        }

        // create directory
        if (!file_exists(FCPATH .'assets/img/members/'. $oauth2_username)) {
            mkdir(FCPATH .'assets/img/members/'. $oauth2_username);
        }else{
            $this->session->set_flashdata('error', $this->lang->line('create_imgfolder_failed'));
            redirect($newUrl);
        }

        // send confirmation email
        $this->load->helper('send_email');
        $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
        $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
        $this->email->to($this->input->post('email'));
        $this->email->set_mailtype("html");

        // approval needed?
        if (Settings_model::$db_config['registration_approval_required']) {
            $this->email->subject($this->lang->line('register_email_approve_subject'));

            $this->email->message(
                $this->load->view('generic/email_templates/header.php', array('new_username' => $oauth2_username), true) .
                $this->load->view('themes/bootstrap3/email_templates/oauth2.php', '', true) .
                $this->load->view('generic/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
            );
            $this->email->set_alt_message(
                $this->load->view('generic/email_templates/header-txt.php', array('new_username' => $oauth2_username), true) .
                $this->load->view('themes/bootstrap3/email_templates/oauth2-txt.php', '', true) .
                $this->load->view('generic/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
            );

            $this->email->send();
            $this->session->set_flashdata('success', $this->lang->line('register_email_approve_success'));
            redirect('login');

        }else{
            // set session data and log in
            $this->load->helper('session');
            session_init($userData);

            $this->email->subject($this->lang->line('oauth2_welcome_subject'));

            $this->email->message(
                $this->load->view('generic/email_templates/header.php', array('new_username' => $oauth2_username), true) .
                $this->load->view('themes/bootstrap3/email_templates/oauth2-active.php', '', true) .
                $this->load->view('generic/email_templates/footer.php', array('site_title' => Settings_model::$db_config['site_title']), true)
            );
            $this->email->set_alt_message(
                $this->load->view('generic/email_templates/header-txt.php', array('new_username' => $oauth2_username), true) .
                $this->load->view('themes/bootstrap3/email_templates/oauth2-active-txt.php', '', true) .
                $this->load->view('generic/email_templates/footer-txt.php', array('site_title' => Settings_model::$db_config['site_title']), true)
            );

            $this->email->send();
            $this->session->set_flashdata('success', $this->lang->line('register_email_active_success'));

            redirect('membership/'. strtolower(Settings_model::$db_config['home_page']));
        }

    }

}

