<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Oauth_providers_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_providers
     *
     * @return mixed
     *
     */

    public function get_providers() {
        return $this->db->order_by('oauth_order', 'asc')->get(DB_PREFIX .'oauth_provider')->result();
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
        $this->db->set('date_modified', 'NOW()', FALSE);
        $this->db->where('oauth_provider_id', $data['oauth_provider_id']);
        $this->db->update(DB_PREFIX .'oauth_provider', $data);
        return $this->db->affected_rows();
    }

    /**
     *
     * delete_provider
     *
     * @param int $id
     * @return bool
     *
     */

    public function delete_provider($id) {
        $this->db->where('oauth_provider_id', $id)->delete(DB_PREFIX .'oauth_provider');
        return $this->db->affected_rows();
    }

}