<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_Controller extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        // detect AJAX, end stream when false
        if (!$this->input->is_ajax_request()) {
            redirect(Settings_model::$db_config['home_page']);
        }

        header("content-type:application/json");
    }
}
