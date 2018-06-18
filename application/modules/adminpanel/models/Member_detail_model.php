<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_detail_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_member_data
     *
     * @param int $id
     * @return mixed
     *
     */

    public function get_member_data($id) {
        $this->db->select('u.user_id, u.username, u.email, u.first_name, u.last_name, u.last_login, u.date_registered, u.banned, u.active')
            ->from(DB_PREFIX .'user u')
            ->where('u.user_id', $id)
            ->limit(1);

        $q = $this->db->get();

        if ($q->num_rows() == 1) {
            return $q->row();
        }

        return false;
    }

    /**
     *
     * save
     *
     * @param array $data
     * @return bool
     *
     */

    public function save($data) {
        $this->db->where('user_id', $data['user_id'])
                 ->update(DB_PREFIX .'user', $data);

        return $this->db->affected_rows();
    }

    /**
     *
     * get_username
     *
     * @return mixed
     *
     */

    public function get_username() {
        $this->db->select('username')->from(DB_PREFIX .'user')->where('user_id', $this->input->post('user_id'))->limit(1);
        $q = $this->db->get();
        return $q->row();
    }

    /**
     *
     * update_profile_img
     *
     * @param string $image_name
     * @param string $username
     * @return bool
     *
     */

    public function update_profile_img($image_name, $username) {
        $this->db->where('username', $username)->update(DB_PREFIX .'user', array('profile_img' => $image_name));
        return $this->db->affected_rows();
    }

    public function delete_profile_img($user_id) {
        $this->db->where('user_id', $user_id)->update(DB_PREFIX .'user', array('profile_img' => MEMBERS_GENERIC));
        return $this->db->affected_rows();
    }

}