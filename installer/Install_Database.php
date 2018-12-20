<?php
class Install_Database {

    private $_mysqli;

    // Function to validate the post data
    public function validate_post($data)
    {
        return !empty($data['email'])
        && !empty($data['hostname'])
        && !empty($data['db_username'])
        && !empty($data['database'])
        && !empty($data['dbport'])
        && !empty($data['username'])
        && !empty($data['password']);
    }

    public function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function is_valid_username($username) {
        return preg_match("/^[a-zA-Z0-9._-]+$/", $username);
    }

    public function is_valid_password($password) {
        return preg_match("/[\.\@\#\$\%\^\|\?\*\!\:\-\;\&\+\=\{\}\[\]]/", $password) && (strcspn($password, '0123456789') != strlen($password));
    }

    public function show_message($message) {
        return $message;
    }

    // Function to write the db config file
    public function write_db_config($data) {

        // Config path
        $template_path 	= "database.php";
        $output_path 	= '../application/config/database.php';

        // Open the file
        $database_file = file_get_contents($template_path);

        $new  = str_replace("%HOSTNAME%", $data['hostname'], $database_file);
        $new  = str_replace("%USERNAME%", $data['db_username'], $new);
        $new  = str_replace("%PASSWORD%", $data['db_password'], $new);
        $new  = str_replace("%DATABASE%", $data['database'], $new);
        $new  = str_replace("%DBPORT%", $data['dbport'], $new);
        $new  = str_replace("%DBPREFIX%", $data['db_prefix'], $new);

        // Write the new database.php file
        $handle = fopen($output_path, 'w+');

        // Chmod the file to be sure
        @chmod($output_path, 0777);

        // Verify file permissions
        if(is_writable($output_path)) {
            // Write the file
            if(fwrite($handle, $new)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function _connect_to_db($data) {
        // Connect to the database
        $mysqli = new mysqli($data['hostname'], $data['db_username'], $data['db_password'], $data['database'], $data['dbport']);
        // Check for errors
        if(mysqli_connect_errno()) {
            return false;
        }

        return $mysqli;
    }

    // Function to create the tables and fill them with the default data
    public function create_tables($data)
    {

        session_start();

        if ($this->_mysqli = $this->_connect_to_db($data)) {

            // ci_config
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."ci_config` (
                  `ci_config_id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                  `value` text COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`ci_config_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

            $this->_mysqli->query($sql);

            $sql = "INSERT INTO `". $data['db_prefix'] ."ci_config` (`name`, `value`) VALUES
                    ('uri_protocol', 'REQUEST_URI'),
                    ('url_suffix', ''),
                    ('language', 'english'),
                    ('charset', 'UTF-8'),
                    ('enable_hooks', 'TRUE'),
                    ('subclass_prefix', 'MY_'),
                    ('composer_autoload', 'FALSE'),
                    ('permitted_uri_chars', 'a-z 0-9~%.:_\\-'),
                    ('allow_get_array', 'TRUE'),
                    ('enable_query_strings', 'FALSE'),
                    ('controller_trigger', 'c'),
                    ('function_trigger', 'm'),
                    ('directory_trigger', 'd'),
                    ('log_threshold', '1'),
                    ('log_path', ''),
                    ('log_file_extension', ''),
                    ('log_file_permissions', '0644'),
                    ('log_date_format', 'Y-m-d H:i:s'),
                    ('error_views_path', ''),
                    ('cache_path', ''),
                    ('cache_query_string', 'FALSE'),
                    ('encryption_key', '". $_SESSION['encryption_key'] ."'),
                    ('sess_driver', 'database'),
                    ('sess_cookie_name', '". $_SESSION['cookie_name'] ."'),
                    ('sess_expiration', '0'),
                    ('sess_save_path', 'ci_session'),
                    ('sess_match_ip', 'FALSE'),
                    ('sess_time_to_update', '300'),
                    ('sess_regenerate_destroy', 'FALSE'),
                    ('cookie_prefix', ''),
                    ('cookie_domain', '". $_SESSION['cookie_domain'] ."'),
                    ('cookie_path', '/'),
                    ('cookie_secure', 'FALSE'),
                    ('cookie_httponly', 'FALSE'),
                    ('standardize_newlines', ''),
                    ('global_xss_filtering', ''),
                    ('csrf_protection', 'TRUE'),
                    ('csrf_token_name', 'csrf_token_name'),
                    ('csrf_cookie_name', 'csrf_cookie_name'),
                    ('csrf_expire', '7200'),
                    ('csrf_regenerate', 'TRUE'),
                    ('csrf_exclude_uris', 'array()'),
                    ('csrf_no_regen', 'array()'),
                    ('compress_output', 'FALSE'),
                    ('time_reference', 'local'),
                    ('rewrite_short_tags', 'FALSE'),
                    ('proxy_ips', '');";

            $this->_mysqli->query($sql);

            // ci_session
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."ci_session` (
                    `id` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `user_id` int(11) DEFAULT NULL,
                    `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `timestamp` int(10) NOT NULL DEFAULT '0',
                    `data` blob NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `ci_session_timestamp` (`timestamp`),
                    KEY `ci_session user_id` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $this->_mysqli->query($sql);


            // oauth_provider
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."oauth_provider` (
                    `oauth_provider_id` int(10) NOT NULL AUTO_INCREMENT,
                    `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `oauth_type` tinyint(4) NOT NULL DEFAULT '2',
                    `client_id` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `client_secret` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `enabled` tinyint(1) NOT NULL DEFAULT '1',
                    `oauth_order` mediumint(6) NOT NULL DEFAULT '999',
                    `date_modified` datetime NOT NULL,
                    PRIMARY KEY (`oauth_provider_id`),
                    UNIQUE KEY `name` (`name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $this->_mysqli->query($sql);

            $sql = "INSERT INTO `". $data['db_prefix'] ."oauth_providers` (`name`, `oauth_type`, `client_id`, `client_secret`, `enabled`, `order`) VALUES
                    ('Facebook', 2, '', '', 0, 1),
                    ('Twitter', 1, '', '', 0, 2),
                    ('Google', 2, '', '', 0, 3),
                    ('Microsoft', 2, '', '', 0, 4),
                    ('LinkedIn', 2, '', '', 0, 5),
                    ('Github', 2, '', '', 0, 6),
                    ('Paypal', 2, '', '', 0, 7),
                    ('Stripe', 2, '', '', 0, 8);";

            $this->_mysqli->query($sql);


            // permission
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."permission` (
                    `permission_id` int(11) NOT NULL AUTO_INCREMENT,
                    `permission_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `permission_system` tinyint(1) NOT NULL DEFAULT '0',
                    `permission_order` int(11) NOT NULL,
                    PRIMARY KEY (`permission_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $this->_mysqli->query($sql);

            $sql = "INSERT INTO `". $data['db_prefix'] ."permission` (`permission_id`, `permission_description`, `permission_system`, `permission_order`) VALUES
                    (1, 'View members', 1, 0),
                    (3, 'View settings', 1, 21),
                    (4, 'Add member', 1, 1),
                    (5, 'Edit member', 1, 3),
                    (6, 'Delete members', 1, 4),
                    (7, 'OAuth providers', 1, 10),
                    (8, 'Dashboard', 1, 20),
                    (9, 'Ban and unban members', 1, 6),
                    (10, 'Activate and deactivate members', 1, 5),
                    (11, 'Save settings', 1, 22),
                    (12, 'Clear sessions', 1, 25),
                    (13, 'Manage roles and permissions', 1, 7),
                    (14, 'Backup and export', 1, 30),
                    (15, 'Email member', 1, 8),
                    (16, 'Approve and unapprove members', 1, 5),
                    (17, 'View ci_config', 1, 100),
                    (18, 'Save ci_config', 1, 110),
                    (19, 'Manager CIMembership updates', 1, 999999);";

            $this->_mysqli->query($sql);


            // recover_password
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."recover_password` (
                    `recover_password_id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `token` char(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `date_added` datetime DEFAULT NULL,
                    PRIMARY KEY (`recover_password_id`),
                    KEY `fk_recover_password_user_id_idx` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $this->_mysqli->query($sql);


            // role
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."role` (
                    `role_id` int(11) NOT NULL AUTO_INCREMENT,
                    `role_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `role_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `role_selectable` tinyint(1) NOT NULL DEFAULT '1',
                    PRIMARY KEY (`role_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $this->_mysqli->query($sql);

            $sql = "INSERT INTO `". $data['db_prefix'] ."role` (`role_id`, `role_name`, `role_description`, `role_selectable`) VALUES
                    (1, 'Administrator', 'CAN NOT BE DELETED - All system permissions are active by default.', 1),
                    (2, 'Super Moderator', 'They can do everything except for settings and backups.', 1),
                    (3, 'Moderator', 'They have access to members but can''t delete them.', 1),
                    (4, 'Member', 'CAN NOT BE DELETED - is useful in case you want to give permissions to default members.', 0);";

            $this->_mysqli->query($sql);


            // role_permission
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."role_permission` (
                    `role_id` int(11) NOT NULL,
                    `permission_id` int(11) NOT NULL,
                    PRIMARY KEY (`role_id`,`permission_id`),
                    KEY `fk_role_permission_permission_id_idx` (`permission_id`),
                    KEY `fk_role_permission_role_id_idx` (`role_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."role_permission`
                ADD CONSTRAINT `role_permission role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT `role_permission permission_id` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`permission_id`) ON DELETE CASCADE ON UPDATE CASCADE;
            ";

            $this->_mysqli->query($sql);

            $sql ="INSERT INTO `". $data['db_prefix'] ."role_permission` (`role_id`, `permission_id`) VALUES
                    (1, 1),
                    (1, 3),
                    (1, 4),
                    (1, 5),
                    (1, 6),
                    (1, 7),
                    (1, 8),
                    (1, 9),
                    (1, 10),
                    (1, 11),
                    (1, 12),
                    (1, 13),
                    (1, 14),
                    (1, 15),
                    (1, 16),
                    (1, 17),
                    (1, 18),
                    (1, 19),
                    (2, 1),
                    (2, 4),
                    (2, 5),
                    (2, 6),
                    (2, 7),
                    (2, 8),
                    (2, 9),
                    (2, 10),
                    (2, 13),
                    (2, 15),
                    (2, 16),
                    (2, 17),
                    (3, 1),
                    (3, 4),
                    (3, 5),
                    (3, 8),
                    (3, 9),
                    (3, 10),
                    (3, 16);";

            $this->_mysqli->query($sql);


            // setting
            // ---------------------------------------------------------------------------------------------------------
            $sql ="CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."setting` (
                    `setting_id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                    PRIMARY KEY (`setting_id`),
                    UNIQUE KEY `name` (`name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $this->_mysqli->query($sql);

            $sql = "INSERT INTO `". $data['db_prefix'] ."setting` (`name`, `value`) VALUES
                    ('cim_version', '3.2.4'),
                    ('root_admin_username', '". $data['username'] ."' ),
                    ('admin_email', '". $data['email'] ."'),
                    ('login_enabled', '1'),
                    ('register_enabled', '1'),
                    ('members_per_page', '12'),
                    ('home_page', 'Profile'),
                    ('previous_url_after_login', '0'),
                    ('active_theme', 'bootstrap3'),
                    ('adminpanel_theme', 'adminpanel'),
                    ('login_attempts', '5'),
                    ('max_login_attempts', '30'),
                    ('email_protocol', '3'),
                    ('sendmail_path', '/usr/sbin/sendmail'),
                    ('smtp_host', 'ssl://smtp.googlemail.com'),
                    ('smtp_port', '465'),
                    ('smtp_user', ''),
                    ('smtp_pass', ''),
                    ('site_title', 'CIMembership'),
                    ('cookie_expires', '259200'),
                    ('password_link_expires', '1800'),
                    ('activation_link_expires', '43200'),
                    ('disable_all', '0'),
                    ('site_disabled_text', 'This website is momentarily offline.'),
                    ('remember_me_enabled', '1'),
                    ('recaptchav2_enabled', '0'),
                    ('recaptchav2_site_key', ''),
                    ('recaptchav2_secret', ''),
                    ('oauth_enabled', '1'),
                    ('picture_max_upload_size', '100'),
                    ('allow_login_by_email', '1'),
                    ('registration_requires_password', '1'),
                    ('registration_requires_username', '1'),
                    ('oauth_requires_username', '1'),
                    ('allow_username_change', '1'),
                    ('registration_activation_required', '1'),
                    ('registration_approval_required', '0'),
                    ('change_password_send_email', '1'),
                    ('google_analytics_tracking_code', ''),
                    ('admin_ip_address', '');";

            $this->_mysqli->query($sql);


            // user
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE `". $data['db_prefix'] ."user` (
                  `user_id` int(11) NOT NULL,
                  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `email` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
                  `date_registered` datetime NOT NULL,
                  `last_login` datetime NOT NULL,
                  `first_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `last_name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `active` tinyint(1) NOT NULL DEFAULT '0',
                  `approved` tinyint(1) NOT NULL DEFAULT '1',
                  `banned` tinyint(1) NOT NULL DEFAULT '0',
                  `login_attempts` tinyint(4) NOT NULL DEFAULT '0',
                  `gender` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `profile_img` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'members_generic.png',
                  `last_updated` datetime NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

            $this->_mysqli->query($sql);

            $sql = "INSERT INTO `". $data['db_prefix'] ."user` (`user_id`, `username`, `password`, `email`, `date_registered`, `last_login`,
            `first_name`, `last_name`, `active`, `login_attempts`) VALUES
                    (1, '". $data['username'] ."', '". password_hash($data['password'], PASSWORD_DEFAULT) ."', '". $data['email'] ."', NOW(), NOW(),
                     '', '', 1, NOW());";

            $this->_mysqli->query($sql);

            // username_history
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."username_history` (
                    `username_history_id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `username` varchar(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `last_updated` datetime NOT NULL,
                    PRIMARY KEY (`username_history_id`),
                    UNIQUE KEY `user_id` (`user_id`,`username`),
                    KEY `fk_username_history_user_id_idx` (`user_id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."username_history`
              ADD CONSTRAINT `username_history user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
            ";

            $this->_mysqli->query($sql);

            $sql = "INSERT INTO `". $data['db_prefix'] ."username_history` (`user_id`, `username`) VALUES
                    (1, '". $data['username'] ."');";

            $this->_mysqli->query($sql);


            // user_cookie_part
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."user_cookie_part` (
                    `user_id` int(11) NOT NULL,
                    `cookie_part` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    PRIMARY KEY (`user_id`,`cookie_part`,`ip_address`),
                    KEY `fk_user_cookie_part_user_id_idx` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."user_cookie_part`
              ADD CONSTRAINT `user_cookie_part user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
            ";

            $this->_mysqli->query($sql);

            $sql = "INSERT INTO `". $data['db_prefix'] ."user_cookie_part` (`user_id`, `cookie_part`, `ip_address`) VALUES
                    (1, ". md5(uniqid(mt_rand(), true)) .", ". $_SERVER['REMOTE_ADDR'] .");";

            $this->_mysqli->query($sql);

            // user_role
            // ---------------------------------------------------------------------------------------------------------
            $sql = "CREATE TABLE IF NOT EXISTS `". $data['db_prefix'] ."user_role` (
                    `user_id` int(11) NOT NULL,
                    `role_id` int(11) NOT NULL,
                    PRIMARY KEY (`user_id`,`role_id`),
                    KEY `fk_user_role_user_id_idx` (`user_id`),
                    KEY `fk_user_role_role_id_idx` (`role_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."user_role`
              ADD CONSTRAINT `user_role role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
              ADD CONSTRAINT `user_role user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
            ";

            $this->_mysqli->query($sql);

            $sql = "INSERT INTO `". $data['db_prefix'] ."user_role` (`user_id`, `role_id`) VALUES
                    (1, 1),
                    (1, 4);";

            $this->_mysqli->query($sql);


            // other
            $sql = "ALTER TABLE `". $data['db_prefix'] ."recover_password`
              ADD CONSTRAINT `recover_password user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
            ";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."user`
              ADD PRIMARY KEY (`user_id`),
              ADD UNIQUE KEY `email` (`email`),
              ADD UNIQUE KEY `username` (`username`);";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."user`
                MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."ci_session`
              ADD CONSTRAINT `ci_sessions user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."recover_password`
              ADD CONSTRAINT `recover_password user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."username_history`
              ADD CONSTRAINT `username_history user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."user_cookie_part`
              ADD CONSTRAINT `user_cookie_part user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;";

            $this->_mysqli->query($sql);

            $sql = "ALTER TABLE `". $data['db_prefix'] ."user_role`
              ADD CONSTRAINT `user_role role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
              ADD CONSTRAINT `user_role user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;";

            $this->_mysqli->query($sql);

            // Close the connection
            // ---------------------------------------------------------------------------------------------------------
            $this->_mysqli->close();
            return true;
        }

        return false;
    }
}