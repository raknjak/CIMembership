<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activate_account_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * activate_member
     *
     * @param string $email e-mail address of member
     * @param string $cookie_part
     * @return boolean
     *
     */

    public function activate_member($email, $cookie_part) {
        $this->db->select('u.user_id, u.email, u.active, u.banned, u.date_registered,
                           unix_timestamp(NOW()) - unix_timestamp(u.last_login) < '. Settings_model::$db_config['activation_link_expires'] .' AS timediff')
            ->from(DB_PREFIX .'user u')
            ->join(DB_PREFIX .'user_cookie_part ucp', 'ucp.user_id = u.user_id')
            ->where(array('u.email' => $email, 'ucp.cookie_part' => $cookie_part));

        $q = $this->db->get();

        if ($q->num_rows() == 0) { // if no match account doest exist
            return "nomatch";
        }elseif($q->num_rows() == 1) { // if match then check for banned and active account

            $row = $q->row();

            // is account banned?
            if ($row->banned == 1) {
                return "banned";
            }

            // is account active?
            if ($row->active == 1) {
                return "active";
            }

            // so if it is active is the timestamp still valid?
            if ($row->timediff != 1) {
                // timestamp expired!
                return "expired";
            }else{
                // timestamp is ok -> everything is ok to activate account
                $data = array('active' => true);
				$this->db->where('user_id', $row->user_id);
                $this->db->update(DB_PREFIX .'user', $data);
                if($this->db->affected_rows() == 1) {
                    return "validated";
                }
            }
        }

        return false;
    }
}
