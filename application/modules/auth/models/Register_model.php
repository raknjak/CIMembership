<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        }

    /**
     *
     * create_member
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $first_name
     * @param string $last_name
     * @param bool $active
     * @return mixed
     *
     */

    public function create_member($username, $password, $email, $first_name = null, $last_name = null, $active = false) {

        $cookie_part = md5(uniqid(mt_rand(), true));

        $data = array(
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'active' => $active,
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

        $this->db->insert(DB_PREFIX .'user_cookie_part',
            array('user_id' => $last_id,
                  'cookie_part' => $cookie_part,
                  'ip_address' => $this->input->ip_address()
            )
        );

        $this->db->set('last_updated', 'NOW()', FALSE);
        $this->db->insert(DB_PREFIX .'username_history',
            array('user_id' => $last_id,
                'username' => $username
            )
        );

        $this->db->trans_complete();

        if (! $this->db->trans_status() === false)
        {
            return array('cookie_part' => $cookie_part, 'user_id' => $last_id);
        }

        return false;
    }

}

