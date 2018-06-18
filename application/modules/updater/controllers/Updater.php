<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Updater extends Private_Controller {

    private $_updater_version = "3.2.3";

    public function __construct()
    {
        parent::__construct();
        if (! self::check_roles(1)) {
            redirect("/adminpanel/no_access");
        }
        $this->lang->load('updater');
    }

    public function index() {

        $content_data = array('version' => $this->_updater_version);

        $this->quick_page_setup(
            Settings_model::$db_config['adminpanel_theme'],
            'adminpanel',
            $this->lang->line('updater_title'),
            'updater',
            'header',
            'footer',
            '',
            $content_data
        );
    }

    public function update_now() {

        if (!isset(Settings_model::$db_config['cim_version'])) {
            Settings_model::$db_config['cim_version'] = "3.1.3";
        }

        if (isset(Settings_model::$db_config['cim_version']) && Settings_model::$db_config['cim_version'] == $this->_updater_version) {
            $this->session->set_flashdata('error', 'The system can\'t upgrade to same version! Have you uploaded the most recent updater files? Maybe it is already installed?');
            redirect('updater');
        }

        $this->load->dbforge();

        // todo: should we turn off login access first? or at least check whether the admin has turned it off?

        // remove current cache file
        unlink(APPPATH .'cache/settings.cache');


        // detect versions and load the correct migration accordingly
        // -------------------------------------------------------------------------------------------------------------

        switch (Settings_model::$db_config['cim_version']) {
            case '3.1.3':
                $this->load->model('updater/Update_313_model');
                $this->Update_313_model->execute();
                break;
            case '3.2.0':
                $this->load->model('updater/Update_320_model');
                $this->Update_320_model->execute();
                break;
            case '3.2.1':
                $this->load->model('updater/Update_321_model');
                $this->Update_321_model->execute();
                break;
            case '3.2.3':
                $this->load->model('updater/Update_322_model');
                $this->Update_322_model->execute();
            case '3.2.4':
                $this->load->model('updater/Update_323_model');
                $this->Update_322_model->execute();
        }

        $this->_config_recreation(); // might have to be put inside switch below in future versions?

        // todo: overwrite files automatically, make installer fully automatic

        redirect('login');

    }


    private function _config_recreation() {
        // create new config.php file
        // -------------------------------------------------------------------------------------------------------------
        $template_path 	= APPPATH ."modules/updater/config/config_template.php";
        $output_path 	= APPPATH .'config/config.php';

        // backup config.php
        copy($output_path, APPPATH .'config/config-backup-'. time() . mt_rand() . $this->session->userdata('user_id') .'.php');

        // Open the file
        $config_file = file_get_contents($template_path);

        // Prep data
        $new = str_replace("%uri_protocol%", $this->config->item('uri_protocol'), $config_file);
        $new = str_replace("%url_suffix%", $this->config->item('url_suffix'), $new);
        $new = str_replace("%language%", $this->config->item('language'), $new);
        $new = str_replace("%charset%", $this->config->item('charset'), $new);
        $new = str_replace("'%enable_hooks%'", "TRUE", $new);
        $new = str_replace("%subclass_prefix%", $this->config->item('subclass_prefix'), $new);
        $new = str_replace("'%composer_autoload%'", $this->config->item('composer_autoload') ? "TRUE" : "FALSE", $new);
        $new = str_replace("%permitted_uri_chars%", $this->config->item('permitted_uri_chars'), $new);
        $new = str_replace("'%allow_get_array%'", $this->config->item('allow_get_array') ? "TRUE" : "FALSE", $new);
        $new = str_replace("'%enable_query_strings%'", $this->config->item('enable_query_strings') ? "TRUE" : "FALSE", $new);
        $new = str_replace("%controller_trigger%", $this->config->item('controller_trigger'), $new);
        $new = str_replace("%function_trigger%", $this->config->item('function_trigger'), $new);
        $new = str_replace("%directory_trigger%", $this->config->item('directory_trigger'), $new);
        $new = str_replace("'%log_threshold%'", empty($this->config->item('log_threshold')) ? 0 : $this->config->item('log_threshold'), $new);
        $new = str_replace("%log_path%", $this->config->item('log_path'), $new);
        $new = str_replace("%log_file_extension%", $this->config->item('log_file_extension'), $new);
        $new = str_replace("'%log_file_permissions%'", sprintf("%04d", decoct($this->config->item('log_file_permissions'))), $new);
        $new = str_replace("%log_date_format%", $this->config->item('log_date_format'), $new);
        $new = str_replace("%error_views_path%", $this->config->item('error_views_path'), $new);
        $new = str_replace("%cache_path%", $this->config->item('cache_path'), $new);
        $new = str_replace("'%cache_query_string%'", $this->config->item('cache_query_string') ? "TRUE" : "FALSE", $new);
        $new = str_replace("%encryption_key%", $this->config->item('encryption_key'), $new);
        $new = str_replace("%sess_driver%", $this->config->item('sess_driver'), $new);
        $new = str_replace("%sess_cookie_name%", $this->config->item('sess_cookie_name'), $new);
        $new = str_replace("'%sess_expiration%'", $this->config->item('sess_expiration'), $new);
        $new = str_replace("%sess_save_path%", 'ci_session', $new);
        $new = str_replace("'%sess_match_ip%'", $this->config->item('sess_match_ip') ? "TRUE" : "FALSE", $new);
        $new = str_replace("'%sess_time_to_update%'", $this->config->item('sess_time_to_update'), $new);
        $new = str_replace("'%sess_regenerate_destroy%'", $this->config->item('sess_regenerate_destroy') ? "TRUE" : "FALSE", $new);
        $new = str_replace("%cookie_prefix%", $this->config->item('cookie_prefix'), $new);
        $new = str_replace("%cookie_domain%", $this->config->item('cookie_domain'), $new);
        $new = str_replace("%cookie_path%", $this->config->item('cookie_path'), $new);
        $new = str_replace("'%cookie_secure%'", $this->config->item('cookie_secure') ? "TRUE" : "FALSE", $new);
        $new = str_replace("'%cookie_httponly%'", $this->config->item('cookie_httponly') ? "TRUE" : "FALSE", $new);
        $new = str_replace("%standardize_newlines%", $this->config->item('standardize_newlines'), $new);
        $new = str_replace("%global_xss_filtering%", $this->config->item('global_xss_filtering'), $new);
        $new = str_replace("'%csrf_protection%'", $this->config->item('csrf_protection') ? "TRUE" : "FALSE", $new);
        $new = str_replace("%csrf_token_name%", $this->security->get_csrf_token_name(), $new);
        $new = str_replace("%csrf_cookie_name%", $this->config->item('csrf_cookie_name'), $new);
        $new = str_replace("'%csrf_expire%'", $this->config->item('csrf_expire'), $new);
        $new = str_replace("'%csrf_regenerate%'", $this->config->item('csrf_regenerate') ? "TRUE" : "FALSE", $new);
        $new = str_replace("'%csrf_exclude_uris%'", empty($this->config->item('csrf_exclude_uris')) ? "array()" : "'". implode(',', $this->config->item('csrf_exclude_uris')) ."'", $new);
        $new = str_replace("'%csrf_no_regen%'", empty($this->config->item('csrf_no_regen')) ? "array()" : "'". $this->config->item('csrf_no_regen') ."'", $new);
        $new = str_replace("'%compress_output%'", $this->config->item('compress_output') ? "TRUE" : "FALSE", $new);
        $new = str_replace("%time_reference%", $this->config->item('time_reference'), $new);
        $new = str_replace("'%rewrite_short_tags%'", $this->config->item('rewrite_short_tags') ? "TRUE" : "FALSE", $new);
        $new = str_replace("'%proxy_ips%'", (empty($this->config->item('proxy_ips')) ? "''" : (strpos($this->config->item('proxy_ips'), 'array') !== false ? $this->config->item('proxy_ips') : "'". $this->config->item('proxy_ips') ."'")), $new);


        // Write file
        $handle = fopen($output_path, 'w+');

        if (is_writable($output_path)) {
            // Write the file
            if (!fwrite($handle, $new)) {
                $this->session->set_flashdata('error', 'could not write, fool');
            }
        }else{
            $this->session->set_flashdata('error', 'config.php not writable');
        }


        // create new constants.php file
        // -------------------------------------------------------------------------------------------------------------
        $template_path 	= APPPATH ."modules/updater/config/constants_template.php";
        $output_path 	= APPPATH .'config/constants.php';

        // backup config.php
        copy($output_path, APPPATH .'config/constants-backup-'. time() . mt_rand() . $this->session->userdata('user_id') .'.php');

        // Open the file
        $constants_file = file_get_contents($template_path);

        // Prep data
        $new = str_replace("%SITE_KEY%", SITE_KEY, $constants_file);

        $new = preg_replace('#\r\n?#', "\n", $new);

        // Write file
        $handle = fopen($output_path, 'w+');

        if (is_writable($output_path)) {
            // Write the file
            if (!fwrite($handle, $new)) {
                $this->session->set_flashdata('error', 'could not write');
            }
        }else{
            $this->session->set_flashdata('error', 'constants.php not writable');
        }


        // create new database.php file
        // -------------------------------------------------------------------------------------------------------------

        $template_path 	= APPPATH ."modules/updater/config/database_template.php";
        $output_path 	= APPPATH .'config/database.php';

        // backup config.php
        copy($output_path, APPPATH .'config/database-backup-'. time() . mt_rand() . $this->session->userdata('user_id') .'.php');

        // Open the file
        $database_file = file_get_contents($template_path);

        // Prep data
        $new  = str_replace("%HOSTNAME%", $this->db->hostname, $database_file);
        $new  = str_replace("%USERNAME%", $this->db->username, $new);
        $new  = str_replace("%PASSWORD%", $this->db->password, $new);
        $new  = str_replace("%DATABASE%", $this->db->database, $new);
        $new  = str_replace("%DBPORT%", $this->db->port, $new);
        $new  = str_replace("%DBPREFIX%", $this->db->dbprefix, $new);
        $new  = str_replace("%DBDRIVER%", $this->db->dbdriver, $new);
        $new  = str_replace("'%PCONNECT%'", $this->db->pconnect ? "TRUE" : "FALSE", $new);
        $new  = str_replace("'%DBDEBUG%'", $this->db->db_debug ? "TRUE" : "FALSE", $new);
        $new  = str_replace("'%CACHEON%'", $this->db->cache_on ? "TRUE" : "FALSE", $new);
        $new  = str_replace("%CACHEDIR%", $this->db->cachedir, $new);
        $new  = str_replace("%CHARSET%", $this->db->char_set, $new);
        $new  = str_replace("%DBCOLLAT%", 'utf8_unicode_ci', $new);
        $new  = str_replace("%SWAPPRE%", $this->db->swap_pre, $new);
        $new  = str_replace("'%ENCRYPT%'", $this->db->encrypt ? "TRUE" : "FALSE", $new);
        $new  = str_replace("'%COMPRESS%'", $this->db->compress ? "TRUE" : "FALSE", $new);
        $new  = str_replace("'%STRICTON%'", $this->db->stricton ? "TRUE" : "FALSE", $new);
        $new  = str_replace("'%FAILOVER%'", empty($this->db->failover) ? "array()" : "array('". implode(',', $this->db->failover) ."')", $new);
        $new  = str_replace("'%SAVE_QUERIES%'", $this->db->save_queries ? "TRUE" : "FALSE", $new);

        // Write file
        $handle = fopen($output_path, 'w+');

        if (is_writable($output_path)) {
            // Write the file
            if (!fwrite($handle, $new)) {
                $this->session->set_flashdata('error', 'could not write');
            }
        }else{
            $this->session->set_flashdata('error', 'database.php not writable');
        }
    }

}