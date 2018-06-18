<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Private_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->lang->load('membership');
    }

    public function index() {
        $this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'],
            'adminpanel', // theme
            $this->lang->line('home_title'), // page title
            'home',  // view name
            'header', // partial
            'footer' // partial
        );
    }

}