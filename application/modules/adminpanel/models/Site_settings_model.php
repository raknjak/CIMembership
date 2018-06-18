<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_settings_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * save_settings: save the new settings to the DB
     *
     * @param array $data
     * @return bool
     *
     */

    public function save_settings($data) {

        $this->db->trans_start();

        foreach ($data as $name => $value) {
            $this->db->where('name', $name);
            $this->db->update(DB_PREFIX .'setting', array('value' => $value));

            // optional auto-approve when setting is turned on. Should be fine with large amounts of data its a simple query.
            /*if ($name == "registration_approval_required" && $value == 1) {
                // set approved to true for current members to avoid login issues
                $this->db->update(DB_PREFIX .'user', array('approved' => true));
            }*/
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() !== FALSE)
        {
            return true;
        }
        return false;
    }

    /**
     *
     * clear_sessions: remove all session data
     *
     * @return bool
     *
     */

    public function clear_sessions() {
        $this->db->where('user_id != ', $this->session->userdata('user_id'));
        $this->db->delete(DB_PREFIX .'ci_session');
        return $this->db->affected_rows();
    }
}