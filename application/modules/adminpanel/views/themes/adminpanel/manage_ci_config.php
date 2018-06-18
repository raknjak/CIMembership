<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<p class="lead alert alert-warning f400">
    <strong><i class="fa fa-warning pd-r-5"></i> WARNING:</strong>
    <br>messing around with these settings might break your page temporarily but these can be restored in <code>application/config/config.php</code>.
    <br>
    <span class="sml">When submitting this form a copy of your latest config is saved in <code>application/config/config-backup.php</code>. This
        file will be overwritten so make sure to save it locally on your computer.</span>
    <br>
    <span class="sml">Your last config is also stored in the database in table ci_config.</span>
</p>

<?php $this->load->view('generic/flash_error'); ?>

<?php echo form_open('adminpanel/manage_ci_config/save_config', array('id' => 'save_config_form', 'class' => 'js-parsley', 'data-parsley-submit' => 'save_config_submit')); ?>

    <div class="form-group">
        <label for="uri_protocol">uri_protocol</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="uri_protocol" id="uri_protocol" class="form-control"
                       value="<?php echo $ci_config['uri_protocol']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="url_suffix">url_suffix</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="url_suffix" id="url_suffix" class="form-control"
                       value="<?php echo $ci_config['url_suffix']; ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="language">language</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="language" id="language" class="form-control"
                       value="<?php echo $ci_config['language']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="charset">charset</label>
        <div class="row">
            <div class="col-sm-5 col-md-4 col-lg-3">
                <input type="text" name="charset" id="charset" class="form-control"
                       value="<?php echo $ci_config['charset']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="enable_hooks" class="pd-r-10">
                <input type="checkbox" name="enable_hooks" id="enable_hooks"<?php echo ($ci_config['enable_hooks'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> enable_hooks
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="subclass_prefix">subclass_prefix</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="subclass_prefix" id="subclass_prefix" class="form-control"
                       value="<?php echo $ci_config['subclass_prefix']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="composer_autoload" class="pd-r-10">
                <input type="checkbox" name="composer_autoload" id="composer_autoload"<?php echo ($ci_config['composer_autoload'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> composer_autoload
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="permitted_uri_chars">permitted_uri_chars</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="permitted_uri_chars" id="permitted_uri_chars" class="form-control"
                       value="<?php echo $ci_config['permitted_uri_chars']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="allow_get_array" class="pd-r-10">
                <input type="checkbox" name="allow_get_array" id="allow_get_array"<?php echo ($ci_config['allow_get_array'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> allow_get_array
            </label>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="enable_query_strings" class="pd-r-10">
                <input type="checkbox" name="enable_query_strings" id="enable_query_strings"<?php echo ($ci_config['enable_query_strings'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> enable_query_strings
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="controller_trigger">controller_trigger</label>
        <div class="row">
            <div class="col-sm-2">
                <input type="text" name="controller_trigger" id="controller_trigger" class="form-control"
                       value="<?php echo $ci_config['controller_trigger']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="function_trigger">function_trigger</label>
        <div class="row">
            <div class="col-sm-2">
                <input type="text" name="function_trigger" id="function_trigger" class="form-control"
                       value="<?php echo $ci_config['function_trigger']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="directory_trigger">directory_trigger</label>
        <div class="row">
            <div class="col-sm-2">
                <input type="text" name="directory_trigger" id="directory_trigger" class="form-control"
                       value="<?php echo $ci_config['directory_trigger']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="log_threshold">log_threshold</label>
        <div class="row">
            <div class="col-sm-2">
                <input type="text" name="log_threshold" id="log_threshold" class="form-control"
                       value="<?php echo (!empty($ci_config['log_threshold']) ? (is_array($ci_config['log_threshold']) ? "array(". implode($ci_config['log_threshold']) .")" : $ci_config['log_threshold']) : "0"); ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="log_path">log_path</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="log_path" id="log_path" class="form-control"
                       value="<?php echo $ci_config['log_path']; ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="log_file_extension">log_file_extension</label>
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <input type="text" name="log_file_extension" id="log_file_extension" class="form-control"
                       value="<?php echo $ci_config['log_file_extension']; ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="log_file_permissions">log_file_permissions</label>
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <input type="text" name="log_file_permissions" id="log_file_permissions" class="form-control"
                       value="<?php echo $ci_config['log_file_permissions']; ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="log_date_format">log_date_format</label>
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <input type="text" name="log_date_format" id="log_date_format" class="form-control"
                       value="<?php echo $ci_config['log_date_format']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="error_views_path">error_views_path</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="error_views_path" id="error_views_path" class="form-control"
                       value="<?php echo $ci_config['error_views_path']; ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="cache_path">cache_path</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="cache_path" id="cache_path" class="form-control"
                       value="<?php echo $ci_config['cache_path']; ?>">
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="cache_query_string" class="pd-r-10">
                <input type="checkbox" name="cache_query_string" id="cache_query_string"<?php echo ($ci_config['cache_query_string'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> cache_query_string
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="encryption_key">encryption_key</label>
        <div class="row">
            <div class="col-sm-8 col-md-6 col-lg-4">
                <input type="text" name="encryption_key" id="encryption_key" class="form-control"
                       value="<?php echo $ci_config['encryption_key']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="sess_driver">sess_driver</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="sess_driver" id="sess_driver" class="form-control"
                       value="<?php echo $ci_config['sess_driver']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="sess_cookie_name">sess_cookie_name</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="sess_cookie_name" id="sess_cookie_name" class="form-control"
                       value="<?php echo $ci_config['sess_cookie_name']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="sess_expiration">sess_expiration</label>
        <div class="row">
            <div class="col-sm-4 col-md-3 col-lg-2">
                <input type="text" name="sess_expiration" id="sess_expiration" class="form-control"
                       value="<?php echo $ci_config['sess_expiration']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="sess_save_path">sess_save_path</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="sess_save_path" id="sess_save_path" class="form-control"
                       value="<?php echo $ci_config['sess_save_path']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="sess_match_ip" class="pd-r-10">
                <input type="checkbox" name="sess_match_ip" id="sess_match_ip"<?php echo ($ci_config['sess_match_ip'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> sess_match_ip
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="sess_time_to_update">sess_time_to_update</label>
        <div class="row">
            <div class="col-sm-4 col-md-3 col-lg-2">
                <input type="text" name="sess_time_to_update" id="sess_time_to_update" class="form-control"
                       value="<?php echo $ci_config['sess_time_to_update']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="sess_regenerate_destroy" class="pd-r-10">
                <input type="checkbox" name="sess_regenerate_destroy" id="sess_regenerate_destroy"<?php echo ($ci_config['sess_regenerate_destroy'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> sess_regenerate_destroy
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="cookie_prefix">cookie_prefix</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="cookie_prefix" id="cookie_prefix" class="form-control"
                       value="<?php echo $ci_config['cookie_prefix']; ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="cookie_domain">cookie_domain</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="cookie_domain" id="cookie_domain" class="form-control"
                       value="<?php echo $ci_config['cookie_domain']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="cookie_path">cookie_path</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="cookie_path" id="cookie_path" class="form-control"
                       value="<?php echo $ci_config['cookie_path']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="cookie_secure" class="pd-r-10">
                <input type="checkbox" name="cookie_secure" id="cookie_secure"<?php echo ($ci_config['cookie_secure'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> cookie_secure
            </label>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="cookie_httponly" class="pd-r-10">
                <input type="checkbox" name="cookie_httponly" id="cookie_httponly"<?php echo ($ci_config['cookie_httponly'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> cookie_httponly
            </label>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="standardize_newlines" class="pd-r-10">
                <input type="checkbox" name="standardize_newlines" id="standardize_newlines"<?php echo ($ci_config['standardize_newlines'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> standardize_newlines
            </label>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="global_xss_filtering" class="pd-r-10">
                <input type="checkbox" name="global_xss_filtering" id="global_xss_filtering"<?php echo ($ci_config['global_xss_filtering'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> global_xss_filtering
            </label>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="csrf_protection" class="pd-r-10">
                <input type="checkbox" name="csrf_protection" id="csrf_protection"<?php echo ($ci_config['csrf_protection'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> csrf_protection
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="ci_csrf_token_name">csrf_token_name</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="ci_csrf_token_name" id="ci_csrf_token_name" class="form-control"
                       value="<?php echo $ci_config['csrf_token_name']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="ci_csrf_cookie_name">csrf_cookie_name</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="ci_csrf_cookie_name" id="ci_csrf_cookie_name" class="form-control"
                       value="<?php echo $ci_config['csrf_cookie_name']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="csrf_expire">csrf_expire</label>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <input type="text" name="csrf_expire" id="csrf_expire" class="form-control"
                       value="<?php echo $ci_config['csrf_expire']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="csrf_regenerate" class="pd-r-10">
                <input type="checkbox" name="csrf_regenerate" id="csrf_regenerate"<?php echo ($ci_config['csrf_regenerate'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> csrf_regenerate
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="csrf_exclude_uris">csrf_exclude_uris</label>
        <div class="row">
            <div class="col-sm-12">
                <input type="text" name="csrf_exclude_uris" id="csrf_exclude_uris" class="form-control"
                       value="<?php echo (!empty($ci_config['csrf_exclude_uris']) ? (is_array($ci_config['csrf_exclude_uris']) ? "array(". implode($ci_config['csrf_exclude_uris']) .")" : $ci_config['csrf_exclude_uris']) : ""); ?>">
            </div>
        </div>
    </div>

    <!--div class="form-group">
        <label for="csrf_no_regen">csrf_no_regen</label>
        <div class="row">
            <div class="col-sm-12">
                <input type="text" name="csrf_no_regen" id="csrf_no_regen" class="form-control"
                       value="<?php $ci_config['csrf_no_regen']; ?>"
                       required>
            </div>
        </div>
    </div-->

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="compress_output" class="pd-r-10">
                <input type="checkbox" name="compress_output" id="compress_output"<?php echo ($ci_config['compress_output'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> compress_output
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="time_reference">time_reference</label>
        <div class="row">
            <div class="col-sm-4 col-md-3 col-lg-2">
                <input type="text" name="time_reference" id="time_reference" class="form-control"
                       value="<?php echo $ci_config['time_reference']; ?>"
                       required>
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="app-checkbox">
            <label for="rewrite_short_tags" class="pd-r-10">
                <input type="checkbox" name="rewrite_short_tags" id="rewrite_short_tags"<?php echo ($ci_config['rewrite_short_tags'] == "TRUE" ? ' checked="checked"' : ""); ?>>
                <span class="fa fa-check pd-r-5"></span> rewrite_short_tags
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="proxy_ips">proxy_ips</label>
        <div class="row">
            <div class="col-sm-12">
                <input type="text" name="proxy_ips" id="proxy_ips" class="form-control"
                       value="<?php echo (!empty($ci_config['proxy_ips']) ? (is_array($ci_config['proxy_ips']) ? "array(". implode($ci_config['proxy_ips']) .")" : $ci_config['proxy_ips']) : ""); ?>">
            </div>
        </div>
    </div>

    <p>
        <button type="submit" name="save_config"
                class="save_config_submit btn btn-primary btn-lg"
                data-loading-text="<?php echo $this->lang->line('manage_ci_config_btn_save_loading'); ?>">
            <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('manage_ci_config_btn_save'); ?>
        </button>
    </p>


<?php echo form_close();


