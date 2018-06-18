<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
     *
     * Database_tools_model: contains generic functions, used in several controllers
     *
     */

class Data_by_email_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_data_by_email: get member data by e-mail address
     *
     * @param string $email the e-mail address to be verified with
     * @return mixed
     *
     */

    public function get_data_by_email($email) {

        $this->db->select('u.user_id, u.username, u.active, ucp.cookie_part')
                 ->from(DB_PREFIX .'user u')
                 ->join(DB_PREFIX .'user_cookie_part ucp', 'ucp.user_id = u.user_id')
                 ->where('u.email', $email)
                 //->where('ucp.ip_address', $this->input->ip_address())
                 ->limit(1);

        $query = $this->db->get();

        if($query->num_rows() == 1) {
            $row = $query->row();
            return array('user_id' => $row->user_id, 'username' => $row->username, 'cookie_part' => $row->cookie_part, 'active' => $row->active);
        }
        return "";
    }

}