<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parsley_custom_validation extends Ajax_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index() {
        redirect(Settings_model::$db_config['home_page']);
    }

    public function parsley_is_db_cell_available() {

        if(!$_GET) {
            print false;
            exit;
        }

        $keys = array_keys($_GET);

        if ($this->form_validation->is_db_cell_available($_GET[$keys[0]], 'user.'. $keys[0])) {
            print 'valid';
            exit;
        }

        print false;
    }
}
