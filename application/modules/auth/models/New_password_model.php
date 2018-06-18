<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_password_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function check_token() {
        $this->db->select('user_id, token, unix_timestamp(NOW()) - unix_timestamp(date_added) < '. Settings_model::$db_config['password_link_expires'] .' AS timediff')
            ->from(DB_PREFIX .'recover_password')
            ->where(array(
                    'token' => $this->uri->segment(3),
                    'email' => urldecode($this->uri->segment(2))
                )
            );
        $q = $this->db->get();

        if ($q->num_rows() == 1) {

            if ($q->row()->timediff != 1) {
                // timestamp expired!
                return "expired";
            }

            return $q->row();
        }

        return false;
    }

    /**
     *
     * change_password: delete all token data for an e-mail address
     *
     * @param string $password
     * @return bool
     *
     */

    public function change_password($password) {

        $this->db->trans_start();
        $this->db->where('user_id', $this->session->flashdata('temp_user_id'))->update(DB_PREFIX .'user', array('password' => password_hash($password, PASSWORD_DEFAULT)));

        $affected_rows = $this->db->affected_rows();

        $this->_delete_token_data($this->session->flashdata('token'));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }

        return $affected_rows;
    }

    /**
     *
     * delete_token_data: delete all token data for an e-mail address
     *
     * @param string $token the token sent to the model comming from the e-mail link
     * @return bool
     *
     */

    private function _delete_token_data($token) {
        $this->db->delete(DB_PREFIX .'recover_password', array('token' => $token));
        return $this->db->affected_rows();
    }

}