<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rbac_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_roles
     *
     * @return mixed
     *
     */

    public function get_roles() {
        $this->db->select('role_id, role_name')->from(DB_PREFIX .'role')->where('role_selectable', true);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    /**
     *
     * get_permissions_by_role_id
     *
     * @param int $role_id
     * @return mixed
     *
     */

    public  function get_permissions_by_role_id($role_id) {
        $this->db->select('p.permission_id')->from(DB_PREFIX .'permission p')->join(DB_PREFIX .'role_permission rp', 'rp.permission_id = p.permission_id')->where('rp.role_id', $role_id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    /**
     *
     * get_member_roles
     *
     * @param int $user_id
     * @return mixed
     *
     */

    public  function get_member_roles($user_id) {
        $this->db->select('ur.role_id, r.role_name')->from(DB_PREFIX .'user_role ur')->join(DB_PREFIX .'role r', 'r.role_id = ur.role_id')->where('ur.user_id', $user_id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    /**
     *
     * get_member_permissions
     *
     * @param int $user_id
     * @return mixed
     *
     */

    public function get_member_permissions($user_id) {
        $this->db->select('p.permission_id, p.permission_description')->from(DB_PREFIX .'permission p')
            ->join(DB_PREFIX .'role_permission rp', 'rp.permission_id = p.permission_id')
            ->join(DB_PREFIX .'user_role ur', 'ur.role_id = rp.role_id')
            ->where('ur.user_id', $user_id);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    /**
     *
     * create_user_role
     *
     * @param array $data
     * @return mixed
     *
     */

    public  function create_user_role($data) {
        $this->db->insert(DB_PREFIX .'user_role', $data);
        return $this->db->affected_rows();
    }

    /**
     *
     * delete_user_roles
     *
     * @param int $user_id
     * @return mixed
     *
     */

    public function delete_user_roles($user_id) {
        $this->db->where('user_id', $user_id)->where('role_id != 4')->delete(DB_PREFIX .'user_role');
        return $this->db->affected_rows();
    }

    /**
     *
     * add_role_to_member
     *
     * @param int $selected_role represents role id
     * @return mixed
     *
     */

    public function add_role_to_member($selected_role) {
        $this->db->insert(DB_PREFIX .'user_role', array('user_id' => $this->input->post('user_id'), 'role_id' => $selected_role));
        return $this->db->affected_rows();
    }

    /**
     *
     * get_roles
     *
     * @param array $delete_arr
     * @return mixed
     *
     */

    public function delete_unchecked_roles($delete_arr) {
        $this->db->where('user_id', $this->input->post('user_id'));
        $this->db->where_not_in('role_id', $delete_arr);
        $this->db->delete(DB_PREFIX .'user_role');
        return $this->db->affected_rows();
    }

}
