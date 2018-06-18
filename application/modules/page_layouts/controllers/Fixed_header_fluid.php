<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fixed_header_fluid extends Site_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel-fixed-header-fluid', 'Fixed header fluid', 'fixed_header_fluid', 'header', 'footer', Settings_model::$db_config['active_theme']);
    }

}

