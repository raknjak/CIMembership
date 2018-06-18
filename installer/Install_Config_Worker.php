<?php
class Install_Config_Worker {

    function validate_post($data)
    {
        return !empty($data['encryption_key']) && !empty($data['cookie_name']) && !empty($data['site_key']);
    }

    // Function to show an error
    function show_message($message) {
        return $message;
    }

    function write_config($data) {

        // A: config.php
        $template_path 	= "config.php";
        $output_path 	= '../application/config/config.php';

        // Open the file
        $config_file = file_get_contents($template_path);

        $new = str_replace("%ENCRYPTION_KEY%", $data['encryption_key'], $config_file);
        $new = str_replace("%COOKIE_NAME%", $data['cookie_name'], $new);
        $new = str_replace("%COOKIE_DOMAIN%", $data['cookie_domain'], $new);

        // Write the new database.php file
        $handle = fopen($output_path, 'w+');

        // Verify file permissions
        if(is_writable($output_path)) {
            // Write the file
            if(fwrite($handle, $new)) {
                $this->write_constants($data);
            } else {
                return false;
            }
        }

    }

    function write_constants($data) {

        // B. constants.php
        $template_path 	= "constants.php";
        $output_path 	= '../application/config/constants.php';

        // Open the file
        $config_file = file_get_contents($template_path);

        $new = str_replace("%SITE_KEY%", $data['site_key'], $config_file);

        // Write the new database.php file
        $handle = fopen($output_path, 'w+');

        // Verify file permissions
        if(is_writable($output_path)) {
            // Write the file
            if(fwrite($handle, $new)) {
                return true;
            } else {
                return false;
            }
        }
        return false;

    }

}