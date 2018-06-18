<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

    <?php $this->load->view('generic/flash_error'); ?>

    <h2><?php echo $this->lang->line('export_title'); ?></h2>

    <p class="fg-info f700">
        <span class="form_subtext"><?php echo $this->lang->line('backup_text'); ?></span>
    </p>

    <?php echo form_open('adminpanel/backup_export/export_members', array('id' => 'export_members_form', 'class' => 'js-parsley', 'data-parsley-submit' => 'export_submit')) ."\r\n"; ?>

    <div class="app-checkbox">
        <label class="pd-r-10">
            <?php echo form_checkbox(array('name' => 'email_memberlist', 'id' =>'email_memberlist', 'value' => 'accept', 'checked' => FALSE)); ?>
            <span class="fa fa-check"></span> <?php echo $this->lang->line('export_send'); ?>
        </label>
    </div>

    <p class="mg-t-15">
        <button type="submit"
                name="export_submit"
                id="export_submit"
                class="export_submit message_cleanup btn btn-primary btn-lg"
                data-loading-text="<?php echo $this->lang->line('export_members_loading_text'); ?>">
            <i class="fa fa-list-ol pd-r-5"></i> <?php echo $this->lang->line('export_title'); ?>
        </button>
    </p>

    <?php echo form_close() ."\r\n";

    echo form_open('adminpanel/backup_export/export_database', array('id' => 'export_database_form', 'class' => 'js-parsley', 'data-parsley-submit' => 'db_backup_submit')) ."\r\n"; ?>

    <h2><?php echo $this->lang->line('backup_title'); ?></h2>

    <p>
        <span class="form_subtext"><?php echo $this->lang->line('backup_text'); ?></span><br>
        <span class="form_subtext"><?php echo $this->lang->line('backup_warning1'); ?></span><br>
        <span class="form_subtext"><?php echo $this->lang->line('backup_warning2'); ?></span><br>
    </p>

    <div class="app-checkbox">
        <label class="pd-r-10">
            <?php echo form_checkbox(array('name' => 'email_db', 'id' =>'email_db', 'value' => 'accept', 'checked' => FALSE)); ?>
            <span class="fa fa-check"></span> <?php echo $this->lang->line('export_send'); ?>
        </label>
    </div>

    <p class="mg-t-15">
        <button type="submit"
                name="db_backup_submit"
                id="db_backup_submit"
                class="db_backup_submit message_cleanup btn btn-primary btn-lg"
                data-loading-text="<?php echo $this->lang->line('export_database_loading_text'); ?>">
            <i class="fa fa-database pd-r-5"></i> <?php echo $this->lang->line('export_database_title'); ?>
        </button>
    </p>

    <?php echo form_close() ."\r\n";