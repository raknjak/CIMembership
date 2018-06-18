<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Site_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        redirect('page_layouts/left_menu_fluid');
        // makes use of the default_controller in config.php
        // but you can set a default controller for each module individually
    }

}

