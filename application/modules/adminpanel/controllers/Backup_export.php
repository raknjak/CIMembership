<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup_export extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        if (! self::check_permissions(14)) {
            redirect("/adminpanel/no_access");
        }
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index() {
        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('backup_and_export_title'),
            'backup_export',
            $this->_header,
            $this->_footer
        );
    }

    /**
     *
     * export_members: generate list of members as temp text file
     *
     */

    public function export_members() {
        // select data
        $this->load->model('adminpanel/backup_export_model');
        $data = $this->backup_export_model->get_members();

        // does not work with sendmail !!!
        $this->load->library('email');
        // load the file helper and write the file to your server
        $this->load->helper('file');
        $this->load->library('zip');

        $file_date = date('Y-m-d');
        $filename = "members-". $file_date .".txt";
        $foldername = "tmp/memberlist/";
        $zip_path = FCPATH . $foldername . $filename . md5(uniqid());

        $this->zip->add_data($filename, addslashes($data));
        if ($this->zip->archive($zip_path .'.zip')) {
            $this->session->set_flashdata('success', $this->lang->line('export_members_success'));
        }else{
            $this->session->set_flashdata('error', $this->lang->line('export_members_failed'));
        }

        if ($this->input->post('email_memberlist') != "") {
            $this->email->to(Settings_model::$db_config['admin_email']);
            $this->email->from(Settings_model::$db_config['admin_email']);
            $this->email->subject($this->lang->line('export_email_text_title'));
            $this->email->message($this->lang->line('export_email_text'));
            $this->email->attach($zip_path .'.zip');

            if ($this->email->send())
            {
                $this->session->set_flashdata('success', $this->lang->line('export_members_success_send'));
            }else{
                $this->session->set_flashdata('error', $this->lang->line('export_members_failed_send'));
            }
            $this->email->clear();
        }

        redirect("/adminpanel/backup_export");
    }

    /**
     *
     * export_database: save data output for the whole database to temp text file
     *
     */

    public function export_database() {
        $this->load->helper('file');
        $this->load->library('zip');
        $this->load->dbutil();

        $file_date = date('Y-m-d_h-i-s');

        $prefs = array(
                'format'      => 'zip',             // gzip, zip, txt
                'filename'    => "database_backup-". $file_date .".sql",    // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );

        $backup = $this->dbutil->backup($prefs);

        
        $foldername = "tmp/db_backup/";
        $path = FCPATH . $foldername . $prefs['filename'] . md5(uniqid());

        $this->zip->add_data($prefs['filename'], $backup);
        if ($this->zip->archive($path .".zip")) {
            $this->session->set_flashdata('success', $this->lang->line('export_database_success'));
        }else{
            $this->session->set_flashdata('error', $this->lang->line('export_database_failed'));
        }

        // does not work with sendmail !!!
        $this->load->library('email');

        if ($this->input->post('email_db') != "") {
            $this->email->to(Settings_model::$db_config['admin_email']);
            $this->email->from(Settings_model::$db_config['admin_email']);
            $this->email->subject($this->lang->line('export_database_title'));
            $this->email->message($this->lang->line('export_database_text'));
            $this->email->attach($path .".zip");

            if ($this->email->send())
            {
                $this->session->set_flashdata('success', $this->lang->line('export_database_success_send'));
            }else{
                $this->session->set_flashdata('error', $this->lang->line('export_database_failed_send'));
            }
            $this->email->clear();
        }

        redirect("/adminpanel/backup_export");
    }

}