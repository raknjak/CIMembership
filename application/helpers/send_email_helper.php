<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('load_email_config')) {
    /**
     *
     * load_email_config
     *
     * @param int $i the config type: 1 = PHP mail(); 2 = sendmail; 3 = SMTP
     * @return array
     *
     */
    function load_email_config($i) {
        $CI = & get_instance();
        $CI->load->library('encryption');
        $config = array();
        switch ($i) {
            case 2:
                $config = array(
                    'protocol' => 'sendmail',
                    'mailpath' => Settings_model::$db_config['sendmail_path'],
                    'charset' => "utf-8",
                    'wordwrap' => TRUE,
                    'newline' => "\r\n"
                );
                break;
            case 3:
                $config = array(
                    'protocol' => 'smtp',
                    'smtp_host' => Settings_model::$db_config['smtp_host'],
                    'smtp_port' => Settings_model::$db_config['smtp_port'],
                    'smtp_user' => $CI->encryption->decrypt(Settings_model::$db_config['smtp_user']),
                    'smtp_pass' => $CI->encryption->decrypt(Settings_model::$db_config['smtp_pass']),
                    'smtp_timeout' => 30,
                    'charset' => "utf-8",
                    'newline' => "\r\n"
                );
        }

        return $config;
    }
}


/**
 *
 * get_username_from_email
 *
 * @param string $email
 * @return string
 *
 */

if (!function_exists('get_username_from_email')) {
    function get_username_from_email($email) {
        $CI = & get_instance();

        $CI->db->select('username')->from(DB_PREFIX .'user')->where('email', $email);
        $q = $CI->db->get();
        if ($q->num_rows() == 1) {
            return $q->row()->username;
        }
        return false;
    }
}