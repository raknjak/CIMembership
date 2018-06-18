<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_offline extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    function index() {

        if (Settings_model::$db_config['disable_all']) {
            $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'site_offline', 'Site offline', 'site_offline', 'header', 'footer');
        }else{
            redirect('membership/'. strtolower(Settings_model::$db_config['home_page']));
        }
    }

}
