<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_sessions extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');

        //todo: add permission!!
    }

    public function index() {

        $content_data = array();

        // todo - clear sessions:
        // - last 10 min
        // - last hour
        // - today
        // - this week
        // - month
        // - all
        // - user-specific

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            'Manage sessions',
            'manage_sessions',
            $this->_header,
            $this->_footer,
            '',
            $content_data
        );
    }

}