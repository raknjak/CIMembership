<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_Members_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_members: get the members data
     *
     * @param int $limit db limit (members per page)
     * @param int $offset db offset (current page)
     * @param string $order_by db sort order
     * @param string $sort_order asc or desc
     * @param array $search_data search input
     * @return mixed
     *
     */

    public function get_members($limit = 0, $offset = 0, $order_by = "username", $sort_order = "asc", $search_data) {
        $fields = $this->db->list_fields(DB_PREFIX .'user');
        if (!in_array($order_by, $fields)) return array();
        if (!empty($search_data)) {
            !empty($search_data['username']) ? $data['username'] = $search_data['username'] : "";
            !empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
            !empty($search_data['last_name']) ? $data['last_name'] = $search_data['last_name'] : "";
            !empty($search_data['email']) ? $data['email'] = $search_data['email'] : "";
        }
        $this->db->select('user_id, username, email, first_name, last_name, date_registered, last_login, active, approved, banned, login_attempts, profile_img');
        $this->db->from(DB_PREFIX .'user');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        $this->db->limit($limit, $offset);

        $q = $this->db->get();
        
        if($q->num_rows() > 0) {
            return $q;
        }

        return false;
    }

    /**
     *
     * count_all_members: count all members in the table
     *
     */
    
    public function count_all_members()
    {
        return $this->db->count_all_results(DB_PREFIX .'user');
    }

    /**
     *
     * update_member: update member data
     *
     * @param int $user_id the member id
     * @param string $username the member username
     * @param string $email the member e-mail address
     * @param string $first_name the member first name
     * @param string $last_name the member last name
     * @param bool $change_username do we want to change the username?
     * @param bool $change_email do we want to change the user e-mail?
     * @return mixed
     *
     */

    public function update_member($user_id, $username, $email, $first_name, $last_name, $change_username = false, $change_email = false) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(
                'user_id'       => $user_id,
                'first_name'    => $first_name,
                'last_name'     => $last_name);

        if ($change_username) {
            $data['username'] = $username;
        }
        if ($change_email) {
            $data['email'] = $email;
        }
        $this->db->where('user_id', $user_id);
        $this->db->update(DB_PREFIX .'user', $data);

        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * delete_member: delete a single member
     *
     * @param int $id the member id
     * @return boolean
     *
     */

    public function delete_member($id) {
        // delete member
        $this->db->where('user_id', $id);
        $this->db->delete(DB_PREFIX .'user');

        return $this->db->affected_rows();
    }

    /**
     *
     * get_username_by_id: return username by id
     *
     * @param int $id the member id
     * @return mixed
     *
     */

    public function get_username_by_id($id) {
        $this->db->select('username')->from(DB_PREFIX .'user')->where('user_id', $id);
        $q = $this->db->get();
        if($q->num_rows() == 1) {
            $row = $q->row();
            $q->free_result(); // do not keep in memory
            return $row->username;
        }
        return false;
    }

    /**
     *
     * count_all_search_members: count all members when performing search
     *
     * @param array $search_data
     * @return mixed
     *
     */

    public function count_all_search_members($search_data) {
        $data = array();
        !empty($search_data['username']) ? $data['username'] = $search_data['username'] : "";
        !empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
        !empty($search_data['last_name']) ? $data['last_name'] = $search_data['last_name'] : "";
        !empty($search_data['email']) ? $data['email'] = $search_data['email'] : "";

        $this->db->select('user_id, username, email, first_name, last_name, date_registered, last_login');
        $this->db->from(DB_PREFIX .'user');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by(DB_PREFIX ."users.user_id", "asc");
        return $this->db->count_all_results();
    }

    /**
     *
     * toggle_ban: (un)ban member
     *
     * @param int $user_id the member id
     * @param bool $banned ban or unban?
     * @return bool
     *
     */

    public function toggle_ban($user_id, $banned) {
        $this->db->trans_start();

        // set banned option
        $data = array('banned' => ($banned ? false : true));
        $this->db->where('user_id', $user_id);
        $this->db->update(DB_PREFIX .'user', $data);

        if ($data['banned'] == true) {
            $this->_kill_user_sessions($user_id);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();

    }

    /**
     *
     * toggle_active: (de)activate member
     *
     * @param int $user_id the member id
     * @param string $active activate or deactivate?
     * @return bool
     *
     */

    public function toggle_active($user_id, $active) {

        $this->db->trans_start();

        $data = array(
            'active' => ($active ? false : true)
        );

        if ($data['active'] == true) {
            $data['approved'] = false;
            $this->_kill_user_sessions($user_id);
        }

        $this->db->where('user_id', $user_id);
        $this->db->update(DB_PREFIX .'user', $data);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function toggle_approval($user_id, $approved) {

        $this->db->trans_start();

        $data = array(
            'approved' => ($approved ? false : true),
            'active' => false
        );

        $this->db->where('user_id', $user_id);
        $this->db->update(DB_PREFIX .'user', $data);

        if ($data['approved'] == false) {
            $this->_kill_user_sessions($user_id);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function mass_banning($ids, $action) {

        $this->db->trans_start();

        $this->db->where_in('user_id', $ids)->where('username != ', Settings_model::$db_config['root_admin_username'])->update(DB_PREFIX .'user', array('banned' => ($action == "ban" ? true : false)));

        if ($action == false) {
            $this->_kill_multiple_user_sessions($ids);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function mass_activation($ids, $action) {

        $this->db->trans_start();

        $this->db->where_in('user_id', $ids)->where('username != ', Settings_model::$db_config['root_admin_username'])->update(DB_PREFIX .'user', array('active' => ($action == "activate" ? true : false)));

        if ($action == false) {
            $this->_kill_multiple_user_sessions($ids);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function mass_approval($ids, $action) {

        $this->db->trans_start();

        $this->db->where_in('user_id', $ids)->where('username != ', Settings_model::$db_config['root_admin_username'])->update(DB_PREFIX .'user', array('approved' => ($action == "approved" ? false : true)));
        $this->db->where_in('user_id', $ids)->where('username != ', Settings_model::$db_config['root_admin_username'])->update(DB_PREFIX .'user', array('active' => ($action == "approved" ? true : false)));

        if ($action == false) {
            $this->_kill_multiple_user_sessions($ids);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    private function _kill_user_sessions($user_id) {
        $this->db->delete('ci_session', array('user_id' => $user_id));
    }

    private function _kill_multiple_user_sessions($ids) {
        $this->db->where_in('user_id', $ids)->delete(DB_PREFIX .'ci_session');
    }

}
