<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_dashboard extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        self::$page = "my_dashboard";
    }

    public function index() {
        $this->quick_page_setup(
            Settings_model::$db_config['adminpanel_theme'],
            'adminpanel',
            'My dashboard',
            'my_dashboard',
            'header',
            'footer'
        );
    }

}