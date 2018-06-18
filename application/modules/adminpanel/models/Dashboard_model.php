<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {


    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * count_users
     *
     * @return mixed
     *
     */

    public function count_users() {
        return $this->db->count_all_results(DB_PREFIX .'user');
    }

    /**
     *
     * count_users_this_week
     *
     * @return mixed
     *
     */

    public function count_users_this_week() {
        $this->db->select('count(user_id) as count')
            ->from(DB_PREFIX .'user')
            ->where('YEARWEEK(date_registered, 1) = ', 'YEARWEEK(NOW(), 1)', false);
        $q = $this->db->get();
        return $q->row()->count;
    }

    /**
     *
     * count_users_this_month
     *
     * @return mixed
     *
     */

    public function count_users_this_month() {
        $this->db->select('count(user_id) as count')
            ->from(DB_PREFIX .'user')
            ->where('YEAR(date_registered)', 'YEAR(NOW())', false)
            ->where('MONTH(date_registered)', 'MONTH(NOW())', false);
        $q = $this->db->get();
        return $q->row()->count;
    }

    /**
     *
     * count_users_this_year
     *
     * @return mixed
     *
     */

    public function count_users_this_year() {
        $this->db->select('count(user_id) as count')
            ->from(DB_PREFIX .'user')
            ->where('YEAR(date_registered)', 'YEAR(NOW())', false);
        $q = $this->db->get();
        return $q->row()->count;
    }

    /**
     *
     * get_latest_members
     *
     * @return mixed
     *
     */

    public function get_latest_members($limit) {
        $this->db->select('user_id, username, first_name, last_name, email, date_registered, active, last_login, profile_img')->from(DB_PREFIX .'user');
        $this->db->order_by("date_registered", "desc");
        $this->db->limit($limit);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return false;
    }

}