<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resend_activation_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * update_last_login: update the last time the member logged in
     *
     * @param $username the username to validate against
     * @return boolean
     *
     */

    public function update_last_login($username) {
        $this->db->set('last_login', 'NOW()', FALSE);
        $this->db->where(array('username' => $username));
        $this->db->update(DB_PREFIX .'user');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

}

