<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// prepare session values
if (!function_exists('session_dataprep')) {
    function session_dataprep() {
        return array(
            'user_id', 'username', 'profile_img'
        );
    }
}

// load userdata to session storage
if (!function_exists('session_init')) {
    function session_init($userData) {
        $data_prep = session_dataprep();
        $array = array();
        foreach ($data_prep as $value) {
            $array[$value] = $userData->$value;
        }
        set_session_data($array);
    }
}

// the actual factory that churns out session data indiscriminately
if (!function_exists('set_session_data')) {
    function set_session_data($data) {
        $CI = & get_instance();

        foreach($data as $key => $value) {
            if (is_array($value)) {
                set_session_data($value);
            }
            $CI->session->set_userdata($key, $value);
        }

        // get the current session and update it with the user_id value.
        $session_id = session_id();
        $CI->db->where('id', $session_id);
        $CI->db->update(DB_PREFIX .'ci_session', array('user_id' => $data['user_id']));
    }
}

// unset userdata
if (!function_exists('unset_session_data')) {
    function unset_session_data() {
        $CI = & get_instance();
        $data_prep = session_dataprep();
        foreach ($data_prep as $name) {
            $CI->session->unset_userdata($name);
        }
        $CI->load->helper('cookie');
        delete_cookie('unique_token');
    }
}