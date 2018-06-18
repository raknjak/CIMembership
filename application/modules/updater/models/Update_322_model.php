<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_322_model extends CI_Model
{

    private $_version = "3.2.3";

    public function __construct()
    {
        parent::__construct();
    }

    public function execute() {

        $this->db->where('name', 'cim_version')->update(DB_PREFIX .'setting', array('name' => $this->_version));

        die('You only have to replace the files, no database operation needed.');

    }

}