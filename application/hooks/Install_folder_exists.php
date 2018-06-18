<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install_folder_exists
{

    /**
     * check_folder_for_existence: function to find whether the install and migration folder exists
     *
     */

    public function check_folder_for_existence($filenames) {

        $CI =& get_instance();

        foreach ($filenames as $filename) {
            if (file_exists($filename)) {
                $CI->session->set_flashdata('hook_error_'. $filename, strtoupper($filename) . " FOLDER STILL EXISTS - PLEASE REMOVE!");
            }
        }

    }
}