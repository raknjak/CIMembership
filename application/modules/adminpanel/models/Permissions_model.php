<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_permissions
     *
     * @return mixed
     *
     */

    public function get_permissions() {
        return $this->db->order_by('permission_order')->get(DB_PREFIX .'permission')->result();
    }

    /**
     *
     * save
     *
     * @param int $id
     * @param array $data
     * @return bool
     *
     */

    public function save($id, $data) {

        $this->db->select('permission_system')->from(DB_PREFIX .'permission')->where('permission_id', $id);
        $q = $this->db->get();

        if ($q->num_rows() == 1) {
            if ($q->row()->permission_system == 0) {
                return "system";
            }
        }

        $this->db->where(array('permission_id' => $id, 'permission_system' => 0))->update(DB_PREFIX .'permission', $data);
        return $this->db->affected_rows();
    }

    /**
     *
     * delete
     *
     * @param int $id
     * @return bool
     *
     */

    public function delete($id) {

        $this->db->select('permission_system')->from(DB_PREFIX .'permission')->where('permission_id', $id);
        $q = $this->db->get();

        if ($q->num_rows() == 1) {
            if ($q->row()->permission_system == 1) {
                return "system";
            }
        }

        $this->db->trans_start();

        $this->db->where('permission_id', $id)->delete(DB_PREFIX .'role_permission');
        $this->db->where('permission_id', $id)->delete(DB_PREFIX .'permission');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }

        return $this->db->affected_rows();
    }

    /**
     *
     * create
     *
     * @param array $data
     * @return bool
     *
     */

    public function create($data) {
        $this->db->insert(DB_PREFIX .'permission', $data);
        return $this->db->affected_rows();
    }

}

