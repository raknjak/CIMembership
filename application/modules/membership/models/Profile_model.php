<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_profile: get the member pages
     *
     * @return mixed
     *
     */

    public function get_profile() {
        $this->db->select('user_id, first_name, last_name, email');
        $this->db->from(DB_PREFIX .'user');
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->limit(1);

        $query = $this->db->get();

        if($query->num_rows() == 1) {
            return $query->row();
        }
        return false;
    }

    /**
     *
     * set_profile: update pages
     *
     * @param string $data
     * @return mixed
     *
     */

    public function set_profile($data) {
        $this->db->trans_start();

        // get current email
        $new_email = false;
        $this->db->select('u.email, ucp.cookie_part')
                    ->from(DB_PREFIX .'user u')
                    ->join(DB_PREFIX .'user_cookie_part ucp', 'ucp.user_id = u.user_id')
                    ->where('u.user_id', $this->session->userdata('user_id'));
        $q = $this->db->get();

        // update user data
        $this->db->set('last_updated', 'now()', false);
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->update(DB_PREFIX .'user', $data);

        // do username history
        $sql = $this->db->insert_string(DB_PREFIX .'username_history', array('username' => $data['username'], 'user_id' => $this->session->userdata('user_id'))) . ' ON DUPLICATE KEY UPDATE last_updated=now()';
        $this->db->query($sql);

        // if email differs, log out and deactivate
        if ($q->row()->email != $data['email']) {
            $this->_destroy_sessions_and_set_inactive();
            $new_email = true;
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            if ($new_email) {
                return array('newemail' => true, 'cookie_part' => $q->row()->cookie_part);
            }
            else{
                return array('cookie_part' => $q->row()->cookie_part);
            }
        }

        return false;
    }

    /**
     *
     * set_password: update member password
     *
     * @param string $password
     * @return boolean
     *
     */

    public function set_password($password) {
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->update(DB_PREFIX .'user', array(
            'password' => password_hash($password, PASSWORD_DEFAULT))
        );

        return $this->db->affected_rows();
    }
	
	/**
     *
     * delete_membership: remove a whole user account
     *
     * @return bool
     *
     */
	 
	public function delete_membership() {

        $this->db->trans_start();

        // removing roles (isn't required anymore now that we use cascading in MySQL but keeping it here anyway - might disappear in the future)
        $this->db->where('user_id', $this->session->userdata('user_id'))->delete(DB_PREFIX .'user_role');

		$this->db->where('user_id', $this->session->userdata('user_id'))->delete(DB_PREFIX .'user');

        $this->db->trans_complete();
        return $this->db->trans_status();
	}


    /**
     *
     * update_profile_img
     *
     * @return bool
     *
     */

    public function update_profile_img($image_name) {
        $this->db->where('user_id', $this->session->userdata('user_id'))->update(DB_PREFIX .'user', array('profile_img' => $image_name));
        return $this->db->affected_rows();
    }

    /**
     *
     * delete_profile_img to reset the profile image in the DB to members_generic.png
     *
     * @return bool
     *
     */

    public function delete_profile_img() {
        $this->db->where('user_id', $this->session->userdata('user_id'))->update(DB_PREFIX .'user', array('profile_img' => MEMBERS_GENERIC));
        return $this->db->affected_rows();
    }

    private function _destroy_sessions_and_set_inactive() {
        $this->db->trans_start();
        $this->db->where('user_id', $this->session->userdata('user_id'))->delete(DB_PREFIX .'ci_session');
        $this->db->where('user_id', $this->session->userdata('user_id'))->update(DB_PREFIX .'user', array('active' => false));
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

}