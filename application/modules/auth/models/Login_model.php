<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    /**
     *
     * validate_login: check login data against database information
     *
     * @param string $identification the username to be validated
     * @param string $password the password to be validated
     * @param bool $emailAllowed whether the user can log on with email address or not
     * @param $from_cookies
     * @param $cookie_part
     * @param $oauth
     * @param $oauth_email
     * @return mixed
     *
     */

    public function validate_login($identification = null, $password = null, $emailAllowed = false, $from_cookies = false, $cookie_part = null, $oauth = false, $oauth_email = null) {

        // first check if it is login and check for null values
        if ($from_cookies == false && $oauth == false && (is_null($identification) || is_null($password))) {
            return false;
        }

        // select hash from username or email if allowed
        $a = array('u.user_id', 'u.username', 'u.password', 'u.active', 'u.approved', 'u.banned', 'u.profile_img', 'u.login_attempts');

        if ($oauth == true) {
            array_push($a, 'ucp.cookie_part');
        }
        $this->db->select($a);
        $this->db->from(DB_PREFIX .'user u');

        // get cookiedata only
        if ($from_cookies === true) {
            $this->db->join(DB_PREFIX .'user_cookie_part ucp', 'u.user_id = ucp.user_id');
            $this->db->where('ucp.cookie_part', $cookie_part);
            $this->db->where('ucp.ip_address', $this->input->ip_address());
        }elseif ($oauth === true) {
            $this->db->join(DB_PREFIX .'user_cookie_part ucp', 'u.user_id = ucp.user_id')
                ->where('u.email', $oauth_email);
        }else{
            $this->db->where('u.username', $identification);
            if ($emailAllowed) {
                $this->db->or_where('u.email', $identification);
            }
        }

        $this->db->limit(1);

        $q = $this->db->get();

        // get cookiedata only
        if ($from_cookies === true || $oauth == true) {
            if($q->num_rows() == 1) {
                return $q->row();
            }
            return false;
        }

        if($q->num_rows() == 1) {
            // we got some feedback from the database: member is found

            $row = $q->row();

            // check password against hash
            if (password_verify($password, $row->password)) {
                // Login successful.

                $this->db->trans_start();

                if (password_needs_rehash($row->password, PASSWORD_DEFAULT)) {
                    // Recalculate a new password_hash() and overwrite the one we stored previously
                    $hash = password_hash($row->password, PASSWORD_DEFAULT);
                    $this->db->where('user_id', $row->user_id)->update(DB_PREFIX .'user', array('password' => $hash));
                }

                // get cookie part - used to allow members to log in from multiple IP addresses and stay logged in
                $this->db->select('cookie_part')->from(DB_PREFIX .'user_cookie_part')->where(array('user_id' => $row->user_id, 'ip_address' => $this->input->ip_address()));
                $cookie_query = $this->db->get();

                $cookie_part = md5(uniqid(mt_rand(), true)); // doesn't have to be secure as it doesn't hold sensitive data

                if($cookie_query->num_rows() == 1) {
                    // previous login found from this IP - update user cookie part
                    $this->db->where(array('user_id' => $row->user_id, 'ip_address' => $this->input->ip_address()));
                    $this->db->update(DB_PREFIX .'user_cookie_part', array('cookie_part' => $cookie_part));
                }else{
                    // not previously logged in - issue a new log on this IP
                    $this->db->insert(DB_PREFIX .'user_cookie_part', array('user_id' => $row->user_id, 'cookie_part' => $cookie_part, 'ip_address' => $this->input->ip_address()));
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() !== FALSE)
                {
                    // update last login datetime
                    $this->_update_last_login($row->user_id);
                    $this->_clean_recover_password($row->user_id);
                    $row->cookie_part = $cookie_part;
                    return $row;
                }
            }

            // Login failed: increase login attempts by 1
            $this->_increase_login_attempts($row->user_id);
            return ($row->login_attempts + 1); // by not returning an object the login will fail
        }

        return false;
    }

    /**
     *
     * _update_last_login: update the last time the member logged in
     *
     * @param int $user_id
     * @return boolean
     *
     */

    private function _update_last_login($user_id) {
        $this->db->set('last_login', 'NOW()', FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update(DB_PREFIX .'user', array('login_attempts' => 0));
        return $this->db->affected_rows();
    }

    /**
     *
     * _increase_login_attempts: add +1 to login attempts for member
     *
     * @param int $user_id
     * @return boolean
     *
     */

    private function _increase_login_attempts($user_id) {
        $this->db->set('login_attempts', 'login_attempts + 1', FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update(DB_PREFIX .'user');
        return $this->db->affected_rows();
    }

    /**
     *
     * reset_login_attempts: bring login attempts back to 0 for this member
     *
     * @param int $user_id
     * @return boolean
     *
     */

    public function reset_login_attempts($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->update(DB_PREFIX .'user', array('login_attempts' => 0));
    }

    /**
     *
     * check_max_logins: is this member over the predefined allowed max login attempts count from DB?
     *
     * @param int $user_id
     * @return bool
     *
     */

    public function check_max_logins($user_id) {
        $this->db->select('login_attempts')->from(DB_PREFIX .'user')->where('user_id', $user_id);
        $q = $this->db->get();

        if ($q->num_rows() == 1) {
            if ($q->row()->login_attempts >= Settings_model::$db_config['max_login_attempts']) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * _clean_recover_password: at successful login check for existing password recovery rows
     *
     * @param int $user_id
     * @return bool
     *
     */

    private function _clean_recover_password($user_id) {
        $this->db->where('user_id', $user_id)->delete(DB_PREFIX .'recover_password');
    }

}
