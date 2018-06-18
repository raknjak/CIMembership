<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_ci_config extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index() {

        if (! self::check_permissions(17)) {
            redirect("/adminpanel/no_access");
        }


        // todo: to model
        $this->db->select('name, value')->from(DB_PREFIX .'ci_config');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $content_data['ci_config'][$row->name] = $row->value;
            }
        }

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('manage_ci_config_title'),
            'manage_ci_config',
            $this->_header,
            $this->_footer,
            '',
            $content_data
        );
    }

    public function save_config() {

        if (! self::check_permissions(18)) {
            redirect("/adminpanel/no_access");
        }

        // validate
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('uri_protocol', 'uri_protocol', 'trim|required');
        $this->form_validation->set_rules('url_suffix', 'url_suffix', 'trim');
        $this->form_validation->set_rules('language', 'language', 'trim|required');
        $this->form_validation->set_rules('charset', 'charset', 'trim|required');
        $this->form_validation->set_rules('enable_hooks', 'enable_hooks', 'trim|alpha');
        $this->form_validation->set_rules('subclass_prefix', 'subclass_prefix', 'trim|required');
        $this->form_validation->set_rules('composer_autoload', 'composer_autoload', 'trim|alpha');
        $this->form_validation->set_rules('permitted_uri_chars', 'permitted_uri_chars', 'trim|required');
        $this->form_validation->set_rules('allow_get_array', 'allow_get_array', 'trim|alpha');
        $this->form_validation->set_rules('enable_query_strings', 'enable_query_strings', 'trim|alpha');
        $this->form_validation->set_rules('controller_trigger', 'controller_trigger', 'trim|required');
        $this->form_validation->set_rules('function_trigger', 'function_trigger', 'trim|required');
        $this->form_validation->set_rules('directory_trigger', 'directory_trigger', 'trim|required');
        $this->form_validation->set_rules('log_threshold', 'log_threshold', 'trim');
        $this->form_validation->set_rules('log_path', 'log_path', 'trim');
        $this->form_validation->set_rules('log_file_extension', 'log_file_extension', 'trim');
        $this->form_validation->set_rules('log_file_permissions', 'log_file_permissions', 'trim');
        $this->form_validation->set_rules('log_date_format', 'log_date_format', 'trim|required');
        $this->form_validation->set_rules('error_views_path', 'error_views_path', 'trim');
        $this->form_validation->set_rules('cache_path', 'cache_path', 'trim');
        $this->form_validation->set_rules('cache_query_string', 'cache_query_string', 'trim|alpha');
        $this->form_validation->set_rules('encryption_key', 'encryption_key', 'trim|required');
        $this->form_validation->set_rules('sess_driver', 'sess_driver', 'trim|required');
        $this->form_validation->set_rules('sess_cookie_name', 'sess_cookie_name', 'trim|required');
        $this->form_validation->set_rules('sess_expiration', 'sess_expiration', 'trim|required');
        $this->form_validation->set_rules('sess_save_path', 'sess_save_path', 'trim|required');
        $this->form_validation->set_rules('sess_match_ip', 'sess_match_ip', 'trim|alpha');
        $this->form_validation->set_rules('sess_time_to_update', 'sess_time_to_update', 'trim|required');
        $this->form_validation->set_rules('sess_regenerate_destroy', 'sess_regenerate_destroy', 'trim|alpha');
        $this->form_validation->set_rules('cookie_prefix', 'cookie_prefix', 'trim');
        $this->form_validation->set_rules('cookie_domain', 'cookie_domain', 'trim|required');
        $this->form_validation->set_rules('cookie_path', 'cookie_path', 'trim|required');
        $this->form_validation->set_rules('cookie_secure', 'cookie_secure', 'trim|alpha');
        $this->form_validation->set_rules('cookie_httponly', 'cookie_httponly', 'trim|alpha');
        $this->form_validation->set_rules('standardize_newlines', 'standardize_newlines', 'trim|alpha');
        $this->form_validation->set_rules('global_xss_filtering', 'global_xss_filtering', 'trim|alpha');
        $this->form_validation->set_rules('csrf_protection', 'csrf_protection', 'trim|alpha');
        $this->form_validation->set_rules('ci_csrf_token_name', 'csrf_token_name', 'trim|required');
        $this->form_validation->set_rules('ci_csrf_cookie_name', 'csrf_cookie_name', 'trim|required');
        $this->form_validation->set_rules('csrf_expire', 'csrf_expire', 'trim|required');
        $this->form_validation->set_rules('csrf_regenerate', 'csrf_regenerate', 'trim|alpha');
        $this->form_validation->set_rules('csrf_exclude_uris', 'csrf_exclude_uris', 'trim');
        //$this->form_validation->set_rules('csrf_no_regen', 'csrf_no_regen', 'trim');
        $this->form_validation->set_rules('compress_output', 'compress_output', 'trim|alpha');
        $this->form_validation->set_rules('time_reference', 'time_reference', 'trim|required');
        $this->form_validation->set_rules('rewrite_short_tags', 'rewrite_short_tags', 'trim|alpha');
        $this->form_validation->set_rules('proxy_ips', 'proxy_ips', 'trim');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/manage_ci_config');
        }

//var_dump($_POST);die;
        // A: config.php
        $template_path 	= APPPATH ."config/config_template.php";
        $output_path 	= APPPATH .'config/config.php';
        $dbArray = array();

        // back up last config
        copy($output_path, APPPATH .'config/config-backup.php');

        // Open the file
        $config_file = file_get_contents($template_path);

        $new = str_replace("%uri_protocol%", $this->input->post('uri_protocol'), $config_file);
        $this->config->set_item('uri_protocol', $this->input->post('uri_protocol'));
        $dbArray['uri_protocol'] = $this->input->post('uri_protocol');

        $new = str_replace("%url_suffix%", $this->input->post('url_suffix'), $new);
        $this->config->set_item('url_suffix', $this->input->post('url_suffix'));
        $dbArray['url_suffix'] = $this->input->post('url_suffix');

        $new = str_replace("%language%", $this->input->post('language'), $new);
        $this->config->set_item('language', $this->input->post('language'));
        $dbArray['language'] = $this->input->post('language');

        $new = str_replace("%charset%", $this->input->post('charset'), $new);
        $this->config->set_item('charset', $this->input->post('charset'));
        $dbArray['charset'] = $this->input->post('charset');

        $new = str_replace("'%enable_hooks%'", $this->input->post('enable_hooks') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('enable_hooks', $this->input->post('enable_hooks') ? TRUE : FALSE);
        $dbArray['enable_hooks'] = $this->input->post('enable_hooks') ? "TRUE" : "FALSE";

        $new = str_replace("%subclass_prefix%", $this->input->post('subclass_prefix'), $new);
        $this->config->set_item('subclass_prefix', $this->input->post('subclass_prefix'));
        $dbArray['subclass_prefix'] = $this->input->post('subclass_prefix');

        $new = str_replace("'%composer_autoload%'", $this->input->post('composer_autoload') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('composer_autoload', $this->input->post('composer_autoload') ? TRUE : FALSE);
        $dbArray['composer_autoload'] = $this->input->post('composer_autoload') ? "TRUE" : "FALSE";

        $new = str_replace("%permitted_uri_chars%", $this->input->post('permitted_uri_chars'), $new);
        $this->config->set_item('permitted_uri_chars', $this->input->post('permitted_uri_chars'));
        $dbArray['permitted_uri_chars'] = $this->input->post('permitted_uri_chars');

        $new = str_replace("'%allow_get_array%'", $this->input->post('allow_get_array') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('allow_get_array', $this->input->post('allow_get_array') ? TRUE : FALSE);
        $dbArray['allow_get_array'] = $this->input->post('allow_get_array') ? "TRUE" : "FALSE";

        $new = str_replace("'%enable_query_strings%'", $this->input->post('enable_query_strings') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('enable_query_strings', $this->input->post('enable_query_strings') ? TRUE : FALSE);
        $dbArray['enable_query_strings'] = $this->input->post('enable_query_strings') ? "TRUE" : "FALSE";

        $new = str_replace("%controller_trigger%", $this->input->post('controller_trigger'), $new);
        $this->config->set_item('controller_trigger', $this->input->post('controller_trigger'));
        $dbArray['controller_trigger'] = $this->input->post('controller_trigger');

        $new = str_replace("%function_trigger%", $this->input->post('function_trigger'), $new);
        $this->config->set_item('function_trigger', $this->input->post('function_trigger'));
        $dbArray['function_trigger'] = $this->input->post('function_trigger');

        $new = str_replace("%directory_trigger%", $this->input->post('directory_trigger'), $new);
        $this->config->set_item('directory_trigger', $this->input->post('directory_trigger'));
        $dbArray['directory_trigger'] = $this->input->post('directory_trigger');

        $new = str_replace("'%log_threshold%'", empty($this->input->post('log_threshold')) ? 0 : $this->input->post('log_threshold'), $new);
        $this->config->set_item('log_threshold', $this->input->post('log_threshold'));
        $dbArray['log_threshold'] = empty($this->input->post('log_threshold')) ? 0 : $this->input->post('log_threshold');

        $new = str_replace("%log_path%", $this->input->post('log_path'), $new);
        $this->config->set_item('log_path', $this->input->post('log_path'));
        $dbArray['log_path'] = $this->input->post('log_path');

        $new = str_replace("%log_file_extension%", $this->input->post('log_file_extension'), $new);
        $this->config->set_item('log_file_extension', $this->input->post('log_file_extension'));
        $dbArray['uri_protocol'] = $this->input->post('uri_protocol');

        $new = str_replace("'%log_file_permissions%'", $this->input->post('log_file_permissions'), $new);
        $this->config->set_item('log_file_permissions', $this->input->post('log_file_permissions'));
        $dbArray['log_file_permissions'] = $this->input->post('log_file_permissions');

        $new = str_replace("%log_date_format%", $this->input->post('log_date_format'), $new);
        $this->config->set_item('log_date_format', $this->input->post('log_date_format'));
        $dbArray['log_date_format'] = $this->input->post('log_date_format');

        $new = str_replace("%error_views_path%", $this->input->post('error_views_path'), $new);
        $this->config->set_item('error_views_path', $this->input->post('error_views_path'));
        $dbArray['error_views_path'] = $this->input->post('error_views_path');

        $new = str_replace("%cache_path%", $this->input->post('cache_path'), $new);
        $this->config->set_item('cache_path', $this->input->post('cache_path'));
        $dbArray['cache_path'] = $this->input->post('cache_path');

        $new = str_replace("'%cache_query_string%'", $this->input->post('cache_query_string') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('cache_query_string', $this->input->post('cache_query_string') ? TRUE : FALSE);
        $dbArray['cache_query_string'] = $this->input->post('cache_query_string') ? "TRUE" : "FALSE";

        $new = str_replace("%encryption_key%", $this->input->post('encryption_key'), $new);
        $this->config->set_item('encryption_key', $this->input->post('encryption_key'));
        $dbArray['encryption_key'] = $this->input->post('encryption_key');

        $new = str_replace("%sess_driver%", $this->input->post('sess_driver'), $new);
        $this->config->set_item('sess_driver', $this->input->post('sess_driver'));
        $dbArray['sess_driver'] = $this->input->post('sess_driver');

        $new = str_replace("%sess_cookie_name%", $this->input->post('sess_cookie_name'), $new);
        $this->config->set_item('sess_cookie_name', $this->input->post('sess_cookie_name'));
        $dbArray['sess_cookie_name'] = $this->input->post('sess_cookie_name');

        $new = str_replace("'%sess_expiration%'", $this->input->post('sess_expiration'), $new);
        $this->config->set_item('sess_expiration', $this->input->post('sess_expiration'));
        $dbArray['sess_expiration'] = $this->input->post('sess_expiration');

        $new = str_replace("%sess_save_path%", $this->input->post('sess_save_path'), $new);
        $this->config->set_item('sess_save_path', $this->input->post('sess_save_path'));
        $dbArray['sess_save_path'] = $this->input->post('sess_save_path');

        $new = str_replace("'%sess_match_ip%'", $this->input->post('sess_match_ip') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('sess_match_ip', $this->input->post('sess_match_ip') ? TRUE : FALSE);
        $dbArray['sess_match_ip'] = $this->input->post('sess_match_ip') ? "TRUE" : "FALSE";

        $new = str_replace("'%sess_time_to_update%'", $this->input->post('sess_time_to_update'), $new);
        $this->config->set_item('sess_time_to_update', $this->input->post('sess_time_to_update'));
        $dbArray['sess_time_to_update'] = $this->input->post('sess_time_to_update');

        $new = str_replace("'%sess_regenerate_destroy%'", $this->input->post('sess_regenerate_destroy') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('sess_regenerate_destroy', $this->input->post('sess_regenerate_destroy') ? TRUE : FALSE);
        $dbArray['sess_regenerate_destroy'] = $this->input->post('sess_regenerate_destroy') ? "TRUE" : "FALSE";

        $new = str_replace("%cookie_prefix%", $this->input->post('cookie_prefix'), $new);
        $this->config->set_item('cookie_prefix', $this->input->post('cookie_prefix'));
        $dbArray['cookie_prefix'] = $this->input->post('cookie_prefix');

        $new = str_replace("%cookie_domain%", $this->input->post('cookie_domain'), $new);
        $this->config->set_item('cookie_domain', $this->input->post('cookie_domain'));
        $dbArray['cookie_domain'] = $this->input->post('cookie_domain');

        $new = str_replace("%cookie_path%", $this->input->post('cookie_path'), $new);
        $this->config->set_item('cookie_path', $this->input->post('cookie_path'));
        $dbArray['cookie_path'] = $this->input->post('cookie_path');

        $new = str_replace("'%cookie_secure%'", $this->input->post('cookie_secure') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('cookie_secure', $this->input->post('cookie_secure') ? TRUE : FALSE);
        $dbArray['cookie_secure'] = $this->input->post('cookie_secure') ? "TRUE" : "FALSE";

        $new = str_replace("'%cookie_httponly%'", $this->input->post('cookie_httponly') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('cookie_httponly', $this->input->post('cookie_httponly') ? TRUE : FALSE);
        $dbArray['cookie_httponly'] = $this->input->post('cookie_httponly') ? "TRUE" : "FALSE";

        $new = str_replace("%standardize_newlines%", $this->input->post('standardize_newlines'), $new);
        $this->config->set_item('standardize_newlines', $this->input->post('standardize_newlines'));
        $dbArray['standardize_newlines'] = $this->input->post('standardize_newlines');

        $new = str_replace("%global_xss_filtering%", $this->input->post('global_xss_filtering'), $new);
        $this->config->set_item('global_xss_filtering', $this->input->post('global_xss_filtering'));
        $dbArray['global_xss_filtering'] = $this->input->post('global_xss_filtering');

        $new = str_replace("'%csrf_protection%'", $this->input->post('csrf_protection') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('csrf_protection', $this->input->post('csrf_protection') ? TRUE : FALSE);
        $dbArray['csrf_protection'] = $this->input->post('csrf_protection') ? "TRUE" : "FALSE";

        $new = str_replace("%csrf_token_name%", $this->input->post('ci_csrf_token_name'), $new);
        $this->config->set_item('csrf_token_name', $this->input->post('ci_csrf_token_name'));
        $dbArray['csrf_token_name'] = $this->input->post('ci_csrf_token_name');

        $new = str_replace("%csrf_cookie_name%", $this->input->post('ci_csrf_cookie_name'), $new);
        $this->config->set_item('csrf_cookie_name', $this->input->post('ci_csrf_cookie_name'));
        $dbArray['csrf_cookie_name'] = $this->input->post('ci_csrf_cookie_name');

        $new = str_replace("'%csrf_expire%'", $this->input->post('csrf_expire'), $new);
        $this->config->set_item('csrf_expire', $this->input->post('csrf_expire'));
        $dbArray['csrf_expire'] = $this->input->post('csrf_expire');

        $new = str_replace("'%csrf_regenerate%'", $this->input->post('csrf_regenerate') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('csrf_regenerate', $this->input->post('csrf_regenerate') ? TRUE : FALSE);
        $dbArray['csrf_regenerate'] = $this->input->post('csrf_regenerate') ? "TRUE" : "FALSE";

        $new = str_replace("'%csrf_exclude_uris%'", empty($this->input->post('csrf_exclude_uris')) ? "array()" : $this->input->post('csrf_exclude_uris'), $new);
        $this->config->set_item('csrf_exclude_uris', empty($this->input->post('csrf_exclude_uris')) ? array() : array($this->input->post('csrf_exclude_uris')));
        $dbArray['csrf_exclude_uris'] = empty($this->input->post('csrf_exclude_uris')) ? "array()" : $this->input->post('csrf_exclude_uris');

        $new = str_replace("'%csrf_no_regen%'", empty($this->input->post('csrf_no_regen')) ? "array()" : "array('". $this->input->post('csrf_no_regen') ."')", $new);
        $this->config->set_item('csrf_no_regen', empty($this->input->post('csrf_no_regen')) ? array() : array($this->input->post('csrf_no_regen')));
        $dbArray['csrf_no_regen'] = empty($this->input->post('csrf_no_regen')) ? "array()" : "array('". $this->input->post('csrf_no_regen') ."')";

        $new = str_replace("'%compress_output%'", $this->input->post('compress_output') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('compress_output', $this->input->post('compress_output') ? TRUE : FALSE);
        $dbArray['compress_output'] = $this->input->post('compress_output') ? "TRUE" : "FALSE";

        $new = str_replace("%time_reference%", $this->input->post('time_reference'), $new);
        $this->config->set_item('time_reference', $this->input->post('time_reference'));
        $dbArray['time_reference'] = $this->input->post('time_reference');

        $new = str_replace("'%rewrite_short_tags%'", $this->input->post('rewrite_short_tags') ? "TRUE" : "FALSE", $new);
        $this->config->set_item('rewrite_short_tags', $this->input->post('rewrite_short_tags') ? TRUE : FALSE);
        $dbArray['rewrite_short_tags'] = $this->input->post('rewrite_short_tags') ? "TRUE" : "FALSE";

        $new = str_replace("'%proxy_ips%'", (empty($this->input->post('proxy_ips')) ? "''" : (strpos($this->input->post('proxy_ips'), 'array') !== false ? $this->input->post('proxy_ips') : "'". $this->input->post('proxy_ips') ."'")), $new);
        $this->config->set_item('proxy_ips', empty($this->input->post('proxy_ips')) ? "''" : (strpos($this->input->post('proxy_ips'), 'array') !== false ? $this->input->post('proxy_ips') : "'". $this->input->post('proxy_ips') ."'"));
        $dbArray['proxy_ips'] = empty($this->input->post('proxy_ips')) ? '' : $this->input->post('proxy_ips');


        $this->load->model('manage_ci_config_model');
        if (!$this->manage_ci_config_model->update_ci_config($dbArray)) {
            $this->session->set_flashdata('error', $this->lang->line('manage_ci_config_error'));
        }else{
            $this->session->set_flashdata('success', $this->lang->line('manage_ci_config_success'));
        }

        $handle = fopen($output_path, 'w+');

        if (is_writable($output_path)) {
            // Write the file
            if (!fwrite($handle, $new)) {
                $this->session->set_flashdata('error', $this->lang->line('manage_ci_config_error'));
            }else{
                $this->session->set_flashdata('success', $this->lang->line('manage_ci_config_success'));
            }
        }else{
            $this->session->set_flashdata('error', $this->lang->line('manage_ci_config_unwritable'));
        }

        redirect('adminpanel/manage_ci_config');

    }

}