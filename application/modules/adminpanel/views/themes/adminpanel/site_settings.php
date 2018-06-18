<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<?php
echo form_open('adminpanel/site_settings/clear_sessions', array('id' => 'sessions_form', 'class' => 'js-parsley', 'data-parsley-submit' => 'sessions_submit'));
if ($this->session->flashdata('sessions_message')) {
    echo '<div id="error" class="error_box">'. $this->session->flashdata('sessions_message') ."</div>";
}
?>
    <h2><?php echo $this->lang->line('clear_sessions_title'); ?></h2>
    <p>
        <?php echo $this->lang->line('clear_sessions_title_explanation'); ?>
    </p>
    <p>
        <button type="submit" name="sessions_submit" id="sessions_submit" class="sessions_submit btn btn-danger" data-loading-text="<?php echo $this->lang->line('sessions_loading_text'); ?>"><i class="fa fa-flash pd-r-5"></i> <?php echo $this->lang->line('clear_sessions'); ?></button>
    </p>
<?php echo form_close(); ?>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo $this->lang->line('tab_general'); ?></a></li>
    <li role="presentation"><a href="#login" aria-controls="login" role="tab" data-toggle="tab"><?php echo $this->lang->line('tab_login'); ?></a></li>
    <li role="presentation"><a href="#register" aria-controls="register" role="tab" data-toggle="tab"><?php echo $this->lang->line('tab_register'); ?></a></li>
    <li role="presentation"><a href="#oauth" aria-controls="oauth" role="tab" data-toggle="tab"><?php echo $this->lang->line('tab_oauth'); ?></a></li>
    <li role="presentation"><a href="#members" aria-controls="members" role="tab" data-toggle="tab"><?php echo $this->lang->line('tab_members'); ?></a></li>
    <li role="presentation"><a href="#mail" aria-controls="mail" role="tab" data-toggle="tab"><?php echo $this->lang->line('tab_mail'); ?></a></li>
    <li role="presentation"><a href="#recaptcha" aria-controls="recaptcha" role="tab" data-toggle="tab"><?php echo $this->lang->line('tab_recaptcha'); ?></a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">

    <div role="tabpanel" class="tab-pane active" id="general">

        <?php echo form_open('adminpanel/site_settings/save_general', array('class' => 'js-parsley', 'data-parsley-submit' => 'settings_form_submit_general')); ?>

        <h2><?php echo $this->lang->line('general_settings_title'); ?></h2>

        <div class="form-group clearfix">
            <label for="site_title"><?php echo $this->lang->line('site_title'); ?></label>
            <p><?php echo $this->lang->line('site_title_p'); ?></p>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="site_title" id="site_title" class="form-control"
                           value="<?php echo Settings_model::$db_config['site_title']; ?>"
                           data-parsley-trigger="change keyup"
                           data-parsley-maxlength="60"
                           required>
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="admin_email"><?php echo $this->lang->line('admin_email'); ?></label>
            <p><?php echo $this->lang->line('admin_email_p'); ?></p>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="admin_email" id="admin_email" class="form-control"
                           value="<?php echo Settings_model::$db_config['admin_email']; ?>"
                           data-parsley-type="email"
                           data-parsley-maxlength="254"
                           required>
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="disable_all" class="pd-r-10">
                    <input type="checkbox" name="disable_all" id="disable_all" value="accept"<?php echo (Settings_model::$db_config['disable_all'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('disable_whole_app'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('disable_whole_app_p'); ?>
        </div>

        <div class="form-group">
            <label for="admin_ip_address"><?php echo $this->lang->line('admin_ip_address'); ?></label>
            <p><?php echo $this->lang->line('admin_ip_address_p'); ?></p>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="admin_ip_address" id="admin_ip_address" class="form-control"
                           value="<?php echo Settings_model::$db_config['admin_ip_address']; ?>"
                           required>
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="site_disabled_text"><?php echo $this->lang->line('disabled_text'); ?></label><br>
            <textarea name="site_disabled_text" id="site_disabled_text" class="form-control col-lg-12"><?php echo Settings_model::$db_config['site_disabled_text']; ?></textarea>
        </div>

        <div class="form-group clearfix">
            <label for="members_per_page"><?php echo $this->lang->line('members_per_page'); ?></label>
            <p><?php echo $this->lang->line('members_per_page_p'); ?></p>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-8">
                    <input type="text" name="members_per_page" id="members_per_page" class="form-control"
                           value="<?php echo Settings_model::$db_config['members_per_page']; ?>"
                           maxlength="3"
                           required>
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="active_theme"><?php echo $this->lang->line('active_theme'); ?></label>
            <p><?php echo $this->lang->line('active_theme_p'); ?></p>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="active_theme" id="active_theme" class="form-control"
                           value="<?php echo Settings_model::$db_config['active_theme']; ?>"
                           data-parsley-maxlength="50"
                           required>
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="adminpanel_theme"><?php echo $this->lang->line('adminpanel_theme'); ?></label>
            <p><?php echo $this->lang->line('adminpanel_theme_p'); ?></p>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="adminpanel_theme" id="adminpanel_theme" class="form-control"
                           value="<?php echo Settings_model::$db_config['adminpanel_theme']; ?>"
                           data-parsley-maxlength="40"
                           required>
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="google_analytics_tracking_code"><?php echo $this->lang->line('google_analytics_tracking_code'); ?></label>
            <p><?php echo $this->lang->line('google_analytics_tracking_code_p'); ?></p>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-8 clearfix">
                    <input type="text" name="google_analytics_tracking_code" id="google_analytics_tracking_code" class="form-control"
                           value="<?php echo Settings_model::$db_config['google_analytics_tracking_code']; ?>">
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="cookie_expires"><?php echo $this->lang->line('cookie_expiration'); ?></label>
            <p><?php echo $this->lang->line('cookie_expiration_p'); ?></p>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-8 clearfix">
                    <input type="text" name="cookie_expires" id="cookie_expires" class="form-control"
                           value="<?php echo Settings_model::$db_config['cookie_expires']; ?>"
                           required>
                </div>
            </div>
        </div>

        <p>
            <button type="submit" name="site_settings_submit"
                    class="settings_form_submit_general btn btn-primary btn-lg"
                    data-loading-text="<?php echo $this->lang->line('site_settings_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('save_all_settings'); ?>
            </button>
        </p>

        <?php echo form_close(); ?>

    </div>

    <div role="tabpanel" class="tab-pane" id="login">

        <?php echo form_open('adminpanel/site_settings/save_login', array('class' => 'js-parsley', 'data-parsley-submit' => 'settings_form_submit_login')); ?>

        <h2><?php echo $this->lang->line('login_settings_title'); ?></h2>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="login_enabled" class="pd-r-10">
                    <input type="checkbox" name="login_enabled" id="login_enabled" value="accept"<?php echo (Settings_model::$db_config['login_enabled'] == 0 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('disable_login_access'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('disable_login_access_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="remember_me_enabled" class="pd-r-10">
                    <input type="checkbox" name="remember_me_enabled" id="remember_me_enabled" value="accept"<?php echo (Settings_model::$db_config['remember_me_enabled'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('enable_remember_me'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('enable_remember_me_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="allow_login_by_email" class="pd-r-10">
                    <input type="checkbox" name="allow_login_by_email" id="allow_login_by_email" value="accept"<?php echo (Settings_model::$db_config['allow_login_by_email'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('allow_login_by_email'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('allow_login_by_email_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="previous_url_after_login" class="pd-r-10">
                    <input type="checkbox" name="previous_url_after_login" id="previous_url_after_login" value="accept"<?php echo (Settings_model::$db_config['previous_url_after_login'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('previous_url_after_login'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('previous_url_after_login_p'); ?>
        </div>

        <div class="form-group clearfix">
            <label for="home_page"><?php echo $this->lang->line('post_login_page'); ?></label>
            <p><?php echo $this->lang->line('post_login_page_p'); ?></p>
            <div class="row">
                <div class="col-md-2 col-sm-4 clearfix">
                    <?php echo form_dropdown('home_page', $private_pages, Settings_model::$db_config['home_page'], array('class' => "form-control", 'required' => 'required')); ?>
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="password_link_expires"><?php echo $this->lang->line('password_link_expiration'); ?></label>
            <p><?php echo $this->lang->line('password_link_expiration_p'); ?></p>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-8 clearfix">
                    <input type="text" name="password_link_expires" id="password_link_expires" class="form-control"
                           value="<?php echo Settings_model::$db_config['password_link_expires']; ?>"
                           required>
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="max_login_attempts"><?php echo $this->lang->line('max_login_attempts'); ?></label>
            <p><?php echo $this->lang->line('max_login_attempts_p'); ?></p>
            <div class="row">
                <div class="col-lg-1 col-md-2 col-sm-4 clearfix">
                    <input type="text" name="max_login_attempts" id="max_login_attempts" class="form-control mg-b-10"
                           value="<?php echo Settings_model::$db_config['max_login_attempts']; ?>"
                           maxlength="3"
                           required>
                </div>
            </div>
        </div>

        <p>
            <button type="submit" name="site_settings_submit"
                    class="settings_form_submit_login btn btn-primary btn-lg"
                    data-loading-text="<?php echo $this->lang->line('site_settings_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('save_all_settings'); ?>
            </button>
        </p>

        <?php echo form_close(); ?>
    </div>

    <div role="tabpanel" class="tab-pane" id="register">

        <?php echo form_open('adminpanel/site_settings/save_register', array('class' => 'js-parsley', 'data-parsley-submit' => 'settings_form_submit_register')); ?>

        <h2><?php echo $this->lang->line('register_settings_title'); ?></h2>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="register_enabled" class="pd-r-10">
                    <input type="checkbox" name="register_enabled" id="register_enabled" value="accept"<?php echo (Settings_model::$db_config['register_enabled'] == 0 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('registration_disable'); ?>
                </label>
            </div>
            <p class="form_subtext"><?php echo $this->lang->line('disable_registration_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="registration_requires_password" class="pd-r-10">
                    <input type="checkbox" name="registration_requires_password" id="registration_requires_password" value="accept"<?php echo (Settings_model::$db_config['registration_requires_password'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('registration_requires_password'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('registration_requires_password_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="registration_requires_username" class="pd-r-10">
                    <input type="checkbox" name="registration_requires_username" id="registration_requires_username" value="accept"<?php echo (Settings_model::$db_config['registration_requires_username'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('registration_requires_username'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('registration_requires_username_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="registration_activation_required" class="pd-r-10">
                    <input type="checkbox" name="registration_activation_required" id="registration_activation_required" value="accept"<?php echo (Settings_model::$db_config['registration_activation_required'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('registration_activation_required'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('registration_activation_required_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="registration_approval_required" class="pd-r-10">
                    <input type="checkbox" name="registration_approval_required" id="registration_approval_required" value="accept"<?php echo (Settings_model::$db_config['registration_approval_required'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('registration_approval_required'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('registration_approval_required_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <label for="activation_link_expires"><?php echo $this->lang->line('activation_link_expiration'); ?></label>
            <p><?php echo $this->lang->line('activation_link_expiration_p'); ?></p>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-8 clearfix">
                    <input type="text" name="activation_link_expires" id="activation_link_expires" class="form-control"
                           value="<?php echo Settings_model::$db_config['activation_link_expires']; ?>"
                           required>
                </div>
            </div>
        </div>

        <p>
            <button type="submit" name="site_settings_submit"
                    class="settings_form_submit_register btn btn-primary btn-lg"
                    data-loading-text="<?php echo $this->lang->line('site_settings_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('save_all_settings'); ?>
            </button>
        </p>

        <?php echo form_close(); ?>

    </div>

    <div role="tabpanel" class="tab-pane" id="oauth">

        <?php echo form_open('adminpanel/site_settings/save_oauth', array('class' => 'js-parsley', 'data-parsley-submit' => 'settings_form_submit_oauth')); ?>

        <h2><?php echo $this->lang->line('oauth_settings_title'); ?></h2>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="oauth_enabled" class="pd-r-10">
                    <input type="checkbox" name="oauth_enabled" id="oauth_enabled" value="accept"<?php echo (Settings_model::$db_config['oauth_enabled'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('enable_oauth'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('enable_oauth_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="oauth_requires_username" class="pd-r-10">
                    <input type="checkbox" name="oauth_requires_username" id="oauth_requires_username" value="accept"<?php echo (Settings_model::$db_config['oauth_requires_username'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('oauth_requires_username'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('oauth_requires_username_p'); ?></p>
        </div>

        <p>
            <button type="submit" name="site_settings_submit"
                    class="settings_form_submit_oauth btn btn-primary btn-lg"
                    data-loading-text="<?php echo $this->lang->line('site_settings_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('save_all_settings'); ?>
            </button>
        </p>

        <?php echo form_close(); ?>
    </div>

    <div role="tabpanel" class="tab-pane" id="members">

        <?php echo form_open('adminpanel/site_settings/save_members', array('class' => 'js-parsley', 'data-parsley-submit' => 'settings_form_submit_members')); ?>

        <h2><?php echo $this->lang->line('members_settings_title'); ?></h2>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="allow_username_change" class="pd-r-10">
                    <input type="checkbox" name="allow_username_change" id="allow_username_change" value="accept"<?php echo (Settings_model::$db_config['allow_username_change'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('allow_username_change'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('allow_username_change_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="change_password_send_email" class="pd-r-10">
                    <input type="checkbox" name="change_password_send_email" id="change_password_send_email" value="accept"<?php echo (Settings_model::$db_config['change_password_send_email'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('change_password_send_email'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('change_password_send_email_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <label for="picture_max_upload_size"><?php echo $this->lang->line('picture_max_upload_size'); ?></label>
            <p><?php echo $this->lang->line('picture_max_upload_size_p'); ?></p>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-8 clearfix">
                    <input type="text" name="picture_max_upload_size" id="picture_max_upload_size" class="form-control"
                           value="<?php echo Settings_model::$db_config['picture_max_upload_size']; ?>"
                           required>
                </div>
            </div>
        </div>

        <p>
            <button type="submit" name="site_settings_submit"
                    class="settings_form_submit_members btn btn-primary btn-lg"
                    data-loading-text="<?php echo $this->lang->line('site_settings_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('save_all_settings'); ?>
            </button>
        </p>

        <?php echo form_close(); ?>

    </div>

    <div role="tabpanel" class="tab-pane" id="mail">

        <?php echo form_open('adminpanel/site_settings/save_mail', array('class' => 'js-parsley', 'data-parsley-submit' => 'settings_form_submit_mail')); ?>

        <h2><?php echo $this->lang->line('mail_settings_title'); ?></h2>

        <div class="form-group clearfix">

            <div class="app-radio mg-b-5">
                <label for="phpmail">
                    <input type="radio" id="phpmail" name="email_protocol" value="1"<?php echo (Settings_model::$db_config['email_protocol'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="mg-r-5"></span>
                    PHP mail()
                </label>
            </div>
            <div class="app-radio mg-b-5">
                <label for="sendmail">
                    <input type="radio" id="sendmail" name="email_protocol" value="2"<?php echo (Settings_model::$db_config['email_protocol'] == 2 ? ' checked="checked"' : ""); ?>>
                    <span class="mg-r-5"></span>
                    Sendmail
                </label>
            </div>
            <div class="app-radio mg-b-5">
                <label for="gmailsmtp">
                    <input type="radio" id="gmailsmtp" name="email_protocol" value="3"<?php echo (Settings_model::$db_config['email_protocol'] == 3 ? ' checked="checked"' : ""); ?>>
                    <span class="mg-r-5"></span>
                    SMTP (<?php echo $this->lang->line('email_recommended'); ?>)
                </label>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="sendmail_path"><?php echo $this->lang->line('sendmail_path'); ?></label>
            <p><?php echo $this->lang->line('sendmail_path_p'); ?></p>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="sendmail_path" id="sendmail_path" class="form-control"
                           value="<?php echo Settings_model::$db_config['sendmail_path']; ?>">
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="smtp_host"><?php echo $this->lang->line('smtp_host'); ?></label>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="smtp_host" id="smtp_host" class="form-control" value="<?php echo Settings_model::$db_config['smtp_host']; ?>">
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="smtp_port"><?php echo $this->lang->line('smtp_port'); ?></label>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="smtp_port" id="smtp_port" class="form-control" value="<?php echo Settings_model::$db_config['smtp_port']; ?>">
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="smtp_user"><?php echo $this->lang->line('smtp_user'); ?></label>
            <p><?php echo $this->lang->line('smtp_encrypt'); ?></p>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="smtp_user" id="smtp_user" class="form-control" value="<?php echo $this->encryption->decrypt(Settings_model::$db_config['smtp_user']); ?>" autocomplete="off">
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="smtp_pass"><?php echo $this->lang->line('smtp_password'); ?></label>
            <p><?php echo $this->lang->line('smtp_encrypt'); ?></p>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="password" name="smtp_pass" id="smtp_pass" class="form-control" value="<?php echo $this->encryption->decrypt(Settings_model::$db_config['smtp_pass']); ?>" autocomplete="off">
                </div>
            </div>
        </div>

        <p>
            <button type="submit" name="site_settings_submit"
                    class="settings_form_submit_mail btn btn-primary btn-lg"
                    data-loading-text="<?php echo $this->lang->line('site_settings_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('save_all_settings'); ?>
            </button>
        </p>

        <?php echo form_close(); ?>

    </div>

    <div role="tabpanel" class="tab-pane" id="recaptcha">

        <?php echo form_open('adminpanel/site_settings/save_recaptcha', array('class' => 'js-parsley', 'data-parsley-submit' => 'settings_form_submit_recaptcha')); ?>

        <h2><?php echo $this->lang->line('recaptcha_settings_title'); ?></h2>

        <div class="form-group clearfix">
            <div class="app-checkbox">
                <label for="recaptchav2_enabled" class="pd-r-10">
                    <input type="checkbox" name="recaptchav2_enabled" id="recaptchav2_enabled" value="accept"<?php echo (Settings_model::$db_config['recaptchav2_enabled'] == 1 ? ' checked="checked"' : ""); ?>>
                    <span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('enable_recaptcha'); ?>
                </label>
            </div>
            <p><?php echo $this->lang->line('enable_recaptcha_p'); ?></p>
        </div>

        <div class="form-group clearfix">
            <label for="recaptchav2_site_key"><?php echo $this->lang->line('site_key'); ?></label>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="recaptchav2_site_key" id="recaptchav2_site_key" class="form-control"
                           value="<?php echo Settings_model::$db_config['recaptchav2_site_key']; ?>"
                           data-parsley-maxlength="40">
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="recaptchav2_secret"><?php echo $this->lang->line('site_secret'); ?></label>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 clearfix">
                    <input type="text" name="recaptchav2_secret" id="recaptchav2_secret" class="form-control"
                           value="<?php echo Settings_model::$db_config['recaptchav2_secret']; ?>"
                           data-parsley-maxlength="40">
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="login_attempts"><?php echo $this->lang->line('login_attempts_trigger'); ?></label>
            <p><?php echo $this->lang->line('login_attempts_trigger_p'); ?></p>
            <div class="row">
                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-8 clearfix">
                    <input type="text" name="login_attempts" id="login_attempts" class="form-control"
                           value="<?php echo Settings_model::$db_config['login_attempts']; ?>"
                           maxlength="3"
                           required>
                </div>
            </div>
        </div>

        <p>
            <button type="submit" name="site_settings_submit"
                    class="settings_form_submit_recaptcha btn btn-primary btn-lg"
                    data-loading-text="<?php echo $this->lang->line('site_settings_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('save_all_settings'); ?>
            </button>
        </p>

        <?php echo form_close(); ?>

    </div>
</div>