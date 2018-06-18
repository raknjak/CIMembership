<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){

        if (! self::check_permissions(8)) {
            redirect("/adminpanel/no_access");
        }

        // load dashboard data
        $this->load->model('adminpanel/dashboard_model');
        $content_data['total_users'] = $this->dashboard_model->count_users();
        $content_data['new_week'] = $this->dashboard_model->count_users_this_week();
        $content_data['new_month'] = $this->dashboard_model->count_users_this_month();
        $content_data['new_year'] = $this->dashboard_model->count_users_this_year();
        $content_data['latest_members'] = $this->dashboard_model->get_latest_members(10);

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('dashboard_title'),
            'dashboard',
            $this->_header,
            $this->_footer,
            '',
            $content_data);
    }
}