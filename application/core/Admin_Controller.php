<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends Private_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->_theme       = Settings_model::$db_config['adminpanel_theme'];
        $this->_layout      = 'adminpanel';
        $this->_header      = 'header';
        $this->_footer      = 'footer';

        $this->lang->load('adminpanel/adminpanel');
    }

}
