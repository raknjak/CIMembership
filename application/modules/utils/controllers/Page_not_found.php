<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_not_found extends Site_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('page_not_found');
        self::$page = $this->lang->line('pnf_title');
    }

    public function index() {
        $this->output->set_status_header('404'); // setting header to 404
        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('pnf_title'), 'page_not_found', 'header', 'footer');
    }
}