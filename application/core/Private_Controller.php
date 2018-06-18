<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Private_Controller extends Site_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('private');

        $this->output->set_header("HTTP/1.0 200 OK");
        $this->output->set_header("HTTP/1.1 200 OK");
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        $this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');


        // AUTH LOGIC
        // -------------------------------------------------------------------------------------------------------------

        // first of all deny access when login is disabled for all users but admins
        if (!self::check_roles(1)) {
            if(Settings_model::$db_config['login_enabled'] == 0) {
                $this->load->helper('session');
                unset_session_data();
                $this->session->set_flashdata('error', 'Login was disabled by an administrator.');
                redirect('login');
            }elseif(Settings_model::$db_config['disable_all'] == 1) {
                $this->load->helper('session');
                unset_session_data();
                $this->session->set_flashdata('error', 'Site access was disabled by an administrator.');
                redirect('login');
            }
        }

        $this->load->helper('cookie');
        $cookie_domain = config_item('cookie_domain');

        // get cookie data
        $cookie = get_cookie('unique_token');

        // no session data
        if ($this->session->userdata('user_id') == "") {

            if (!$cookie) {
                // no session data, no cookie data found: end now
                setcookie("unique_token", null, time() - 60*60*24*3, '/', $cookie_domain, false, false);
                redirect("login");
            }

            $cookie_part = substr($cookie, -32);

            // check cookie data
            $this->load->model('auth/login_model');
            $userData = $this->login_model->validate_login(null, null, false, true, $cookie_part);

            if (!empty($userData)) {
                // check banned and active
                if ($userData->banned == true) {
                    $this->session->set_flashdata('error', '<p>You are banned.</p>');
                    setcookie("unique_token", null, time() - 60*60*24*3, '/', $cookie_domain, false, false);
                    redirect("login");
                }elseif (Settings_model::$db_config['registration_approval_required'] == true && $userData->approved == false) {
                    $this->session->set_flashdata('error', 'Your account needs to be approved by an admin before you can log in.');
                    setcookie("unique_token", null, time() - 60*60*24*3, '/', $cookie_domain, false, false);
                    redirect("login");
                }elseif(Settings_model::$db_config['registration_activation_required'] == true && $userData->active == false) {
                    $this->session->set_flashdata('error', '<p>Your acount is inactive.</p>');
                    setcookie("unique_token", null, time() - 60*60*24*3, '/', $cookie_domain, false, false);
                    redirect("login");
                }

                // renew cookie
                setcookie("unique_token", get_cookie('unique_token'), time() + Settings_model::$db_config['cookie_expires'], '/', $cookie_domain, false, false);

                // set session data
                $this->load->helper('session');
                session_init($userData);

                // get permissions
                if (empty(self::$roles)) {
                    $this->permissions_roles($userData->user_id);
                }

                if (!self::check_roles(1)) {
                    if(Settings_model::$db_config['disable_all'] == 1) {
                        $this->session->set_flashdata('error', $this->lang->line('site_disabled'));
                        redirect('utils/site_offline');
                    }
                }

                redirect('membership/'. strtolower(Settings_model::$db_config['home_page']));
            }else{
                setcookie("unique_token", null, time() - 60*60*24*3, '/', $cookie_domain, false, false);
                redirect("login");
            }
        }else{
            $this->db->select('active, banned')->from(DB_PREFIX .'user')->where('user_id', $this->session->userdata('user_id'));
            $q = $this->db->get();
            if ($q->row()->active == false) {
                $this->session->set_flashdata('error', 'Your account is inactive.');
                $this->load->helper('session');
                unset_session_data();
                redirect('login');
            }elseif ($q->row()->banned == true) {
                $this->session->set_flashdata('error', 'You are banned.');
                $this->load->helper('session');
                unset_session_data();
                redirect('login');
            }
        }
    }

}
