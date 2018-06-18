<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_all_providers
     *
     * @return mixed
     *
     */

    public function get_all_providers() {
        $this->db->select('oauth_provider_id, name, oauth_type, client_id, client_secret')
            ->from(DB_PREFIX .'oauth_provider')
            ->where('enabled', true)
            ->order_by('oauth_order', 'asc');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return false;
    }

    /**
     *
     * get_provider_data
     *
     * @param string $provider
     * @return mixed
     *
     */

    public function get_provider_data($provider) {
        $this->db->select('client_id, client_secret, enabled')->from(DB_PREFIX .'oauth_provider')->where('name', $provider);
        $q = $this->db->get();

        if ($q->num_rows() == 1) {
            return $q->row();
        }

        return false;
    }

    /**
     *
     * create_member_oauth
     *
     * @param string $username
     * @param string $email
     * @param bool $active
     * @param bool $approved
     * @return mixed
     *
     */

    public function create_member_oauth($username, $email) {

        $cookie_part = md5(uniqid(mt_rand(), true));

        $data = array(
            'username' => $username,
            'email' => $email,
            'active' => true,
            'approved' => true
        );

        if (Settings_model::$db_config['registration_approval_required'] == true) {
            $data['approved'] = false;
        }

        $this->db->trans_start();

        $this->db->set('date_registered', 'NOW()', FALSE);
        $this->db->set('last_login', 'NOW()', FALSE);
        $this->db->insert(DB_PREFIX .'user', $data);

        $last_id = $this->db->insert_id();

        // get data to return to controller
        $returnArray = array();

        $this->db->select('user_id, username, profile_img')->from(DB_PREFIX .'user')->where('user_id', $last_id);
        $q = $this->db->get();

        if ($q->num_rows() == 1) {
            $returnArray['user_id'] = $q->row()->user_id;
            $returnArray['username'] = $q->row()->username;
            $returnArray['profile_img'] = $q->row()->profile_img;
            $returnArray['cookie_part'] = $cookie_part;
        }

        // set username history
        $this->db->set('last_updated', 'NOW()', FALSE);
        $this->db->insert(DB_PREFIX .'username_history',
            array('user_id' => $last_id,
                'username' => $username
            )
        );

        // set cookie part
        $this->db->insert(DB_PREFIX .'user_cookie_part',
            array('user_id' => $last_id, 'cookie_part' => $cookie_part, 'ip_address' => $this->input->ip_address())
        );

        $this->db->trans_complete();

        if (!$this->db->trans_status() === false)
        {

            if (!$this->db->trans_status() === false) {
                return (object) $returnArray;
            }
        }

        return false;
    }

}