<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_member_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_email
     *
     * @return string
     *
     */

    public function get_email() {
        $this->db->select('email')->from(DB_PREFIX .'user')->where('user_id', $this->uri->segment(3))->limit(1);
        $q = $this->db->get();
        return $q->row()->email;
    }

}