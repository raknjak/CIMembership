<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_313_model extends CI_Model
{

    private $_version = "3.2.3";

    public function __construct()
    {
        parent::__construct();
    }

    public function execute() {

        // Start DB migration
        // -------------------------------------------------------------------------------------------------------------

        // --> ci_config
        // -------------------------------------------------------------------------------------------------------------
        $table_name = DB_PREFIX .'ci_config';

        // create
        $fields = array(
            'ci_config_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ),
            'value' => array(
                'type' =>'TEXT',
                'null' => false
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('ci_config_id', TRUE);
        $this->dbforge->create_table($table_name, TRUE);

        // populate
        $data = array(
            array('name' => 'uri_protocol', 'value' => $this->config->item('uri_protocol')),
            array('name' => 'url_suffix', 'value' => $this->config->item('url_suffix')),
            array('name' => 'language', 'value' => $this->config->item('language')),
            array('name' => 'charset', 'value' => $this->config->item('charset')),
            array('name' => 'enable_hooks', 'value' => "TRUE"),
            array('name' => 'subclass_prefix', 'value' => $this->config->item('subclass_prefix')),
            array('name' => 'composer_autoload', 'value' => $this->config->item('composer_autoload') ? "TRUE" : "FALSE"),
            array('name' => 'permitted_uri_chars', 'value' => $this->config->item('permitted_uri_chars')),
            array('name' => 'allow_get_array', 'value' => $this->config->item('allow_get_array') ? "TRUE" : "FALSE"),
            array('name' => 'enable_query_strings', 'value' => $this->config->item('enable_query_strings') ? "TRUE" : "FALSE"),
            array('name' => 'controller_trigger', 'value' => $this->config->item('controller_trigger')),
            array('name' => 'function_trigger', 'value' => $this->config->item('function_trigger')),
            array('name' => 'directory_trigger', 'value' => $this->config->item('directory_trigger')),
            array('name' => 'log_threshold', 'value' => empty($this->config->item('log_threshold')) ? 0 : $this->config->item('log_threshold')),
            array('name' => 'log_path', 'value' => $this->config->item('log_path')),
            array('name' => 'log_file_extension', 'value' => $this->config->item('log_file_extension')),
            array('name' => 'log_file_permissions', 'value' => sprintf("%04d", decoct($this->config->item('log_file_permissions')))),
            array('name' => 'log_date_format', 'value' => $this->config->item('log_date_format')),
            array('name' => 'error_views_path', 'value' => $this->config->item('error_views_path')),
            array('name' => 'cache_path', 'value' => $this->config->item('cache_path')),
            array('name' => 'cache_query_string', 'value' => $this->config->item('cache_query_string') ? "TRUE" : "FALSE"),
            array('name' => 'encryption_key', 'value' => $this->config->item('encryption_key')),
            array('name' => 'sess_driver', 'value' => $this->config->item('sess_driver')),
            array('name' => 'sess_cookie_name', 'value' => $this->config->item('sess_cookie_name')),
            array('name' => 'sess_expiration', 'value' => $this->config->item('sess_expiration')),
            array('name' => 'sess_save_path', 'value' => 'ci_session'),
            array('name' => 'sess_match_ip', 'value' => $this->config->item('sess_match_ip') ? "TRUE" : "FALSE"),
            array('name' => 'sess_time_to_update', 'value' => $this->config->item('sess_time_to_update')),
            array('name' => 'sess_regenerate_destroy', 'value' => $this->config->item('sess_regenerate_destroy') ? "TRUE" : "FALSE"),
            array('name' => 'cookie_prefix', 'value' => $this->config->item('cookie_prefix')),
            array('name' => 'cookie_domain', 'value' => $this->config->item('cookie_domain')),
            array('name' => 'cookie_path', 'value' => $this->config->item('cookie_path')),
            array('name' => 'cookie_secure', 'value' => $this->config->item('cookie_secure') ? "TRUE" : "FALSE"),
            array('name' => 'cookie_httponly', 'value' => $this->config->item('cookie_httponly') ? "TRUE" : "FALSE"),
            array('name' => 'standardize_newlines', 'value' => $this->config->item('standardize_newlines')),
            array('name' => 'global_xss_filtering', 'value' => $this->config->item('global_xss_filtering')),
            array('name' => 'csrf_protection', 'value' => $this->config->item('csrf_protection') ? "TRUE" : "FALSE"),
            array('name' => 'csrf_token_name', 'value' => $this->config->item('csrf_token_name')),
            array('name' => 'csrf_cookie_name', 'value' => $this->config->item('csrf_cookie_name')),
            array('name' => 'csrf_expire', 'value' => $this->config->item('csrf_expire')),
            array('name' => 'csrf_regenerate', 'value' => $this->config->item('csrf_regenerate') ? "TRUE" : "FALSE"),
            array('name' => 'csrf_exclude_uris', 'value' => empty(implode(",", $this->config->item('csrf_exclude_uris'))) ? 'array()' : "array('". implode(",", $this->config->item('csrf_exclude_uris')) ."')"),
            array('name' => 'csrf_no_regen', 'value' => empty($this->config->item('csrf_no_regen')) ? 'array()' : (empty(implode(",", $this->config->item('csrf_no_regen'))) ? 'array()' : implode(",", $this->config->item('csrf_no_regen')))),
            array('name' => 'compress_output', 'value' => $this->config->item('compress_output') ? "TRUE" : "FALSE"),
            array('name' => 'time_reference', 'value' => $this->config->item('time_reference')),
            array('name' => 'rewrite_short_tags', 'value' => $this->config->item('rewrite_short_tags') ? "TRUE" : "FALSE"),
            array('name' => 'proxy_ips', 'value' => empty($this->config->item('proxy_ips')) ? '' : (empty(implode(",", $this->config->item('proxy_ips'))) ? 'array()' : implode(",", $this->config->item('proxy_ips'))))
        );

        foreach($data as $d) {
            $this->db->insert($table_name, $d);
        }

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `value` `value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");

        // engine
        $this->db->query("ALTER TABLE `". $table_name ."` ENGINE = INNODB;");


        // --> oauth_provider
        // -------------------------------------------------------------------------------------------------------------
        $table_name = DB_PREFIX .'oauth_provider';

        // rename
        $this->dbforge->rename_table(DB_PREFIX .'oauth_providers', $table_name);

        // modify
        $fields = array(
            'id' => array(
                'name' => 'oauth_provider_id',
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
                'constraint' => 10,
                'auto_increment' => true
            ),
            'enabled' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'default' => 1
            )
        );
        $this->dbforge->modify_column($table_name, $fields);

        // add
        $fields = array(
            'oauth_type' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'null' => false,
                'default' => 2,
                'after' => 'name'
            ),
            'oauth_order' => array(
                'type' => 'MEDIUMINT',
                'constraint' => 6,
                'null' => false,
                'default' => 999,
                'after' => 'enabled'
            ),
            'date_modified' => array(
                'type' => 'DATETIME',
                'null' => false,
                'after' => 'oauth_order'
            )
        );
        $this->dbforge->add_column($table_name, $fields);

        // fix encryption
        $this->db->select('oauth_provider_id, client_id, client_secret')->from($table_name);
        $q = $this->db->get();

        $this->load->library('encryption');
        foreach($q->result() as $row) {
            $this->db->where('oauth_provider_id', $row->oauth_provider_id)->update($table_name, array(
                'client_id' => $this->encryption->encrypt($row->client_id),
                'client_secret' => $this->encryption->encrypt($row->client_secret))
            );
        }

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `name` `name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `client_id` `client_id` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `client_secret` `client_secret` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");


        // --> permission
        // -----------------------------------------------------
        $table_name = DB_PREFIX .'permission';

        // update data
        $this->db->where('permission_id', 9)->update($table_name, array('permission_description' => 'Ban and unban members'));
        $this->db->where('permission_id', 10)->update($table_name, array('permission_description' => 'Activate and deactivate members'));
        $this->db->where('permission_id', 13)->update($table_name, array('permission_description' => 'Manage roles and permissions'));

        // populate
        $this->db->insert($table_name, array(
                'permission_id' => 16,
                'permission_description' => 'Approve and unapprove members',
                'permission_system' => 1,
                'permission_order' => 5)
        );

        $this->db->insert($table_name, array(
                'permission_id' => 17,
                'permission_description' => 'View ci_config',
                'permission_system' => 1,
                'permission_order' => 100)
        );

        $this->db->insert($table_name, array(
                'permission_id' => 18,
                'permission_description' => 'Save ci_config',
                'permission_system' => 1,
                'permission_order' => 110)
        );

        $this->db->insert($table_name, array(
                'permission_id' => 19,
                'permission_description' => 'Manager CIMembership updates',
                'permission_system' => 1,
                'permission_order' => 999999)
        );

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `permission_description` `permission_description` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");


        // --> recover_password
        // -----------------------------------------------------
        $table_name = DB_PREFIX .'recover_password';

        // modify
        $fields = array(
            'id' => array(
                'name' => 'recover_password_id',
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
                'constraint' => 11,
                'auto_increment' => true
            )
        );
        $this->dbforge->modify_column($table_name, $fields);

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `token` `token` CHAR(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");


        // --> role
        // -----------------------------------------------------
        $table_name = DB_PREFIX .'role';

        // update data
        $this->db->where('role_id', 1)->update($table_name, array('role_description' => 'CAN NOT BE DELETED - All system permissions are active by default.'));

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `role_name` `role_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `role_description` `role_description` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");


        // --> role_permission
        // -----------------------------------------------------
        $table_name = DB_PREFIX .'role_permission';

        // populate
        $this->db->insert($table_name, array(
                'role_id' => 1,
                'permission_id' => 16,
            )
        );

        $this->db->insert($table_name, array(
                'role_id' => 1,
                'permission_id' => 17,
            )
        );

        $this->db->insert($table_name, array(
                'role_id' => 1,
                'permission_id' => 18,
            )
        );

        $this->db->insert($table_name, array(
                'role_id' => 1,
                'permission_id' => 19,
            )
        );

        $this->db->insert($table_name, array(
                'role_id' => 2,
                'permission_id' => 16,
            )
        );

        $this->db->insert($table_name, array(
                'role_id' => 2,
                'permission_id' => 17,
            )
        );

        $this->db->insert($table_name, array(
                'role_id' => 3,
                'permission_id' => 16,
            )
        );

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");


        // --> setting
        // -------------------------------------------------------------------------------------------------------------
        $table_name = DB_PREFIX .'setting';

        // create
        $fields = array(
            'setting_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => false,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ),
            'value' => array(
                'type' =>'TEXT',
                'null' => false
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('setting_id', TRUE);
        $this->dbforge->create_table($table_name, TRUE);


        // get settings data
        $this->db->select('login_enabled, register_enabled, members_per_page, admin_email, home_page, previous_url_after_login,
            active_theme, adminpanel_theme, login_attempts, max_login_attempts, email_protocol, sendmail_path, smtp_host,
            smtp_port, smtp_user, smtp_pass, site_title, cookie_expires, password_link_expires, activation_link_expires,
            site_disabled_text, remember_me_enabled, recaptchav2_enabled, recaptchav2_site_key, recaptchav2_secret, oauth2_enabled')
            ->from(DB_PREFIX .'settings')->where('id', 1);
        $q = $this->db->get();

        // insert
        foreach($q->row() as $k => $row) {
            $this->db->insert(DB_PREFIX .'setting', array('name' => $k, 'value' => $row));
        }

        // insert new
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'disable_all', 'value' => 0));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'picture_max_upload_size', 'value' => 100));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'allow_login_by_email', 'value' => 1));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'cim_version', 'value' => $this->_version));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'root_admin_username', 'value' => 'administrator'));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'registration_requires_password', 'value' => 1));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'registration_requires_username', 'value' => 1));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'oauth_requires_username', 'value' => 1));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'allow_username_change', 'value' => 1));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'registration_activation_required', 'value' => 1));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'registration_approval_required', 'value' => 0));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'change_password_send_email', 'value' => 1));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'google_analytics_tracking_code', 'value' => ''));
        $this->db->insert(DB_PREFIX .'setting', array('name' => 'admin_ip_address', 'value' => ''));

        // drop settings
        $this->dbforge->drop_table(DB_PREFIX .'settings', TRUE);

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `value` `value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");

        // engine
        $this->db->query("ALTER TABLE `". $table_name ."` ENGINE = INNODB;");


        // --> user
        // -----------------------------------------------------
        $table_name = DB_PREFIX .'user';

        // rename
        $this->dbforge->rename_table(DB_PREFIX .'users', $table_name);

        // modify
        $fields = array(
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => NULL
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => NULL
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '254',
                'null' => false
            ),
            'date_registered' => array(
                'type' => 'DATETIME',
                'null' => false
            ),
            'last_login' => array(
                'type' => 'DATETIME',
                'null' => false
            )
        );
        $this->dbforge->modify_column($table_name, $fields);

        // add
        $fields = array(
            'approved' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => '0',
                'null' => false,
                'after' => 'active'
            ),
            'last_updated' => array(
                'type' => 'DATETIME',
                'null' => false,
                'after' => 'profile_img'
            )
        );
        $this->dbforge->add_column($table_name, $fields);

        // drop
        $this->dbforge->drop_column($table_name, 'nonce');
        $this->dbforge->drop_column($table_name, 'cookie_part');

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `username` `username` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `email` `email` VARCHAR(254) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `first_name` `first_name` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `last_name` `last_name` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `gender` `gender` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `profile_img` `profile_img` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'members_generic.png';");


        // --> username_history
        // -----------------------------------------------------
        $table_name = DB_PREFIX .'username_history';

        // create
        $fields = array(
            'username_history_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '24'
            ),
            'last_updated' => array(
                'type' =>'DATETIME'
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('username_history_id', TRUE);
        $this->dbforge->create_table($table_name, TRUE);

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `username` `username` VARCHAR(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");

        // engine
        $this->db->query("ALTER TABLE `". $table_name ."` ENGINE = INNODB;");

        // insert admin
        $this->db->insert($table_name, array('user_id' => 1, 'username' => ADMINISTRATOR, 'last_updated' => 'now()'));

        // --> user_cookie_part
        // -----------------------------------------------------
        $table_name = DB_PREFIX .'user_cookie_part';

        // create
        $fields = array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ),
            'cookie_part' => array(
                'type' => 'CHAR',
                'constraint' => 32
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => 45
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('username_history_id', TRUE);
        $this->dbforge->create_table($table_name, TRUE);

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `cookie_part` `cookie_part` CHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `ip_address` `ip_address` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");

        // engine
        $this->db->query("ALTER TABLE `". $table_name ."` ENGINE = INNODB;");


        // --> user_role
        // -------------------------------------------------------------------------------------------------------------
        $table_name = DB_PREFIX .'user_role';

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");


        // --> INDEXES
        // -------------------------------------------------------------------------------------------------------------
        // username_history keys
        $this->db->query("ALTER TABLE `". DB_PREFIX ."username_history`
                ADD UNIQUE KEY `user_id` (`user_id`,`username`),
                ADD KEY `fk_username_history_user_id_idx` (`user_id`);"
        );


        // DO THIS AS LATE AS POSSIBLE BECAUSE WE RELY ON DB SESSIONS!!
        // --> ci_session
        // -------------------------------------------------------------------------------------------------------------
        $table_name = DB_PREFIX .'ci_session';

        // rename
        $this->dbforge->rename_table(DB_PREFIX .'ci_sessions', $table_name);

        // modify
        $fields = array(
            'id' => array(
                'type' => 'VARCHAR',
                'constraint' => '128'
            )
        );
        $this->dbforge->modify_column($table_name, $fields);

        // add
        $fields = array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'default' => null,
                'after' => 'id'
            )
        );
        $this->dbforge->add_column($table_name, $fields);

        // collation
        $this->db->query("ALTER TABLE `". $table_name ."` DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `id` `id` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        $this->db->query("ALTER TABLE `". $table_name ."` CHANGE `ip_address` `ip_address` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");


        // KEYS
        // -------------------------------------------------------------------------------------------------------------
        $this->db->query("ALTER TABLE `". DB_PREFIX ."recover_password` ADD KEY `fk_recover_password_user_id_idx` (`user_id`);");

        $this->db->query("ALTER TABLE `". DB_PREFIX ."setting` ADD UNIQUE KEY `name` (`name`);");

        $this->db->query("ALTER TABLE `". DB_PREFIX ."user_cookie_part`
          ADD PRIMARY KEY (`user_id`,`cookie_part`,`ip_address`),
          ADD KEY `fk_user_cookie_part_user_id_idx` (`user_id`);");


        // --> CONSTRAINTS
        // -------------------------------------------------------------------------------------------------------------
        // ci_session
        $this->db->query("ALTER TABLE `". DB_PREFIX ."ci_session`
            ADD CONSTRAINT `ci_sessions user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;");

        // recover_password
        $this->db->query("ALTER TABLE `". DB_PREFIX ."recover_password`
          ADD CONSTRAINT `recover_password user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        // role_permission
        $this->db->query("ALTER TABLE `". DB_PREFIX ."role_permission`
          ADD CONSTRAINT `role_permission permission_id` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`permission_id`) ON DELETE CASCADE ON UPDATE CASCADE,
          ADD CONSTRAINT `role_permission role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        // username_history
        $this->db->query("ALTER TABLE `". DB_PREFIX ."username_history` 
        ADD CONSTRAINT `username_history user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;");

        //user_cookie_part
        $this->db->query("ALTER TABLE `". DB_PREFIX ."user_cookie_part`
          ADD CONSTRAINT `user_cookie_part user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        // user_role
        $this->db->query("ALTER TABLE `". DB_PREFIX ."user_role`
          ADD CONSTRAINT `user_role role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
          ADD CONSTRAINT `user_role user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        // DELETE SESSIONS
        $this->db->empty_table(DB_PREFIX .'ci_session');
    }

}