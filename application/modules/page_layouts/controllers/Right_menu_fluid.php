<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Right_menu_fluid extends Site_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel-rtl', 'Right menu fluid', 'right_menu_fluid', 'header_rtl', 'footer', Settings_model::$db_config['active_theme']);
    }

}
