<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<div id="error">
    <div class="alert alert-danger">
        <h4><?php echo $this->lang->line('message_error_heading'); ?></h4>
        <p><?php echo $this->lang->line('no_access_text'); ?></p>
    </div>
</div>