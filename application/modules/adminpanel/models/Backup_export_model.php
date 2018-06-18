<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup_export_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_members: grab all member data and prepare for export
     *
     * @return string
     *
     */

    public function get_members() {
        $this->db->select('u.user_id, u.username, u.email, u.first_name, u.last_name, u.date_registered,
        u.last_login, u.active, u.banned, r.role_name');
        $this->db->from(DB_PREFIX .'user u');
        $this->db->join(DB_PREFIX .'user_role ur', 'ur.user_id = u.user_id');
        $this->db->join(DB_PREFIX .'role r', 'r.role_id = ur.role_id');
        $this->db->order_by('ur.role_id');

        $query = $this->db->get();

        $members = "Username|E-mail address|First name|Last name|Registration date|Last login|Active?|Banned?|Roles\r\n";

        if ($query->num_rows() > 0) {

            $data = array();
            $current = 0;

            foreach ($query->result() as $row) {

                if ($current != $row->user_id) {
                    $current = $row->user_id;
                    $data[$current]['username'] = $row->username;
                    $data[$current]['email'] = $row->email;
                    $data[$current]['first_name'] = $row->first_name;
                    $data[$current]['last_name'] = $row->last_name;
                    $data[$current]['date_registered'] = $row->date_registered;
                    $data[$current]['last_login'] = $row->last_login;
                    $data[$current]['active'] = $row->active;
                    $data[$current]['banned'] = $row->banned;
                }
                $data[$current]['roles'][] = $row->role_name;

            }


            foreach($data as $row) {
                $members .= $row['username'] ."|". $row['email'] ."|". $row['first_name'] ."|". $row['last_name'] ."|". $row['date_registered'] .
                    "|". $row['last_login'] ."|". $row['active'] ."|". $row['banned'] ."|". implode(', ', $row['roles']) ."\r\n";
            }

        }else{
            $members = "no results";
        }

        return $members;
    }


}
