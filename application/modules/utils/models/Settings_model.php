<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model {

    /* is autoloaded */

    public static $db_config = array();

    private static $db;
    private static $CI;

    public function __construct() {
        parent::__construct();
        self::$CI = &get_instance();
        self::$db = &get_instance()->db;
        self::load_settings();
    }

    public static function update_config_value($name, $value) {
        self::$db->where('name', $name)->update(DB_PREFIX .'setting', array('value' => $value));
       // print self::$db->last_query();die;
        return self::$db->affected_rows();
    }

    /**
     *
     * _load_settings: load the settings from database
     *
     *
     */

    public static function load_settings() {
        self::$CI->load->library('cache');
        $data = self::$CI->cache->get('settings');

        if (empty($data)) {

            self::$CI->db->select('name, value')->from(DB_PREFIX .'setting');

            $q = self::$CI->db->get();

            if ($q->num_rows() > 0) {
                foreach($q->result() as $row) {
                    self::$db_config[$row->name] = $row->value;
                }

                self::$CI->cache->write(self::$db_config, 'settings');
            }

        }else{
            foreach($data as $k => $v) {
                self::$db_config[$k] = $v;
            }
        }
    }

}

