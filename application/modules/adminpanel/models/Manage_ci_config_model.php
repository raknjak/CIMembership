<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_ci_config_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function update_ci_config($data) {
        $this->db->trans_start();

        foreach ($data as $k => $v) {
            $this->db->where('name', $k);
            $this->db->update(DB_PREFIX .'ci_config', array('value' => $v));
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

}