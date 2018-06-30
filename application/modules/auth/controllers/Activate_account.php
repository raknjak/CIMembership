<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activate_account extends Auth_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
		redirect('login');
    }

    /**
     *
     * check: verify and activate account
     *
     * @param int $email the e-mail address that received the activation link
     * @param string $cookie_part
     *
     */

    public function check($email = NULL, $cookie_part = NULL) {
        $this->load->library('form_validation');

        $email = urldecode($email);

		if (empty($email)
            || !$this->form_validation->is_valid_email($email)
            || empty($cookie_part)
            || !$this->form_validation->alpha_numeric($cookie_part)
            || !$this->form_validation->exact_length($cookie_part, 32))
        {
			redirect('login');
		}
        
		$this->load->model('auth/activate_account_model');

        $content_data = array();

        $validation = $this->activate_account_model->activate_member($email, $cookie_part);

        switch ($validation) {
            case "nomatch":
                $content_data['error'] = $this->lang->line('activate_account_not_found');
                break;
            case "banned":
                $content_data['error'] = $this->lang->line('account_is_banned');
                break;
            case "active":
                $content_data['error'] = $this->lang->line('account_active');
                break;
            case "expired":
                $content_data['error'] = $this->lang->line('account_activation_link_expired');
                break;
            case "validated":
                $content_data['success'] = $this->lang->line('activate_account_activated');
                break;
            default:
                $content_data['error'] = $this->lang->line('activate_account_error');
        }

        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('activate_account_title'), 'activate_account', 'header', 'footer', '', $content_data);
    }

}
