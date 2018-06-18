<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Oauth_new_provider_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * save_provider
     *
     * @param array $data
     * @return bool
     *
     */

    public function save_provider($data) {
        $this->db->insert(DB_PREFIX .'oauth_provider', $data);
        return $this->db->affected_rows();
    }

}