<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_Controller extends MY_Controller
{
    public static $permissions = array();
    public static $roles = array();
    public static $page;
    private $_meta_description;
    private $_previous_url;

    public function __construct() {
        parent::__construct();

        // first check if site offline
        $this->load->helper('ip');
        if(Settings_model::$db_config['disable_all'] == 1 && !in_array(get_user_ip(), (array) Settings_model::$db_config['admin_ip_address'])) {
            $this->session->set_flashdata('error', Settings_model::$db_config['site_disabled_text']);
            redirect('utils/site_offline');
        }

        // set page name
        self::$page = $this->router->fetch_class();
        $this->lang->load('public');

        if (Settings_model::$db_config['previous_url_after_login']) {
            $this->_previous_url = $this->session->flashdata('current_url');
            $this->session->set_flashdata('previous_url', $this->session->flashdata('current_url'));
            $this->session->set_flashdata('current_url', current_url());
        }

        if ($this->session->userdata('user_id') != "") {
            // get permissions
            $this->lang->load('private_lang');
            $this->permissions_roles($this->session->userdata('user_id'));
        }
    }


    public static function check_roles($role_id) {
        if (!is_array(self::$roles)) {
            return false;
        }
        foreach (self::$roles as $role) {
            if (is_array($role_id)) {
                if (in_array($role->role_id, $role_id)) {
                    return true;
                }
            }elseif ($role->role_id == $role_id) {
                return true;
            }
        }
        return false;
    }

    public static function check_permissions($permission_id) {
        if (!is_array(self::$permissions)) {
            return false;
        }
        foreach (self::$permissions as $permission) {
            if (is_array($permission_id)) {
                if (in_array($permission->permission_id, $permission_id)) {
                    return true;
                }
            }elseif ($permission->permission_id == $permission_id) {
                return true;
            }
        }
        return false;
    }

    public function permissions_roles($user_id) {
        $this->load->model('utils/rbac_model');
        self::$permissions = $this->rbac_model->get_member_permissions($user_id);
        self::$roles = $this->rbac_model->get_member_roles($user_id);
    }

    public function get_meta_description() {
        return $this->_meta_description;
    }

    public function set_meta_description($description) {
        $this->_meta_description = $description;
    }

    public function get_previous_url()
    {
        return($this->_previous_url);
    }

}
