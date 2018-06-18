<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<?php echo form_open('adminpanel/add_member/add', array('id' => 'add_member_form', 'class' => 'js-parsley', 'data-parsley-submit' => 'add_member_submit')); ?>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="first_name"><?php echo $this->lang->line('add_member_first_name'); ?></label>
			<input type="text" name="first_name" id="first_name" class="form-control"
                   value="<?php echo $this->session->flashdata('first_name'); ?>"
                   data-parsley-maxlength="40"
                   required>
		</div>

		<div class="form-group">
			<label for="last_name"><?php echo $this->lang->line('add_member_last_name'); ?></label>
			<input type="text" name="last_name" id="last_name" class="form-control"
                   value="<?php echo $this->session->flashdata('last_name'); ?>"
                   data-parsley-maxlength="60"
                   required>
		</div>

		<div class="form-group">
			<label for="email"><?php echo $this->lang->line('add_member_email_address'); ?></label>
			<input type="text" name="email" id="email" class="form-control"
                   value="<?php echo $this->session->flashdata('email'); ?>"
                   data-parsley-type="email"
                   data-parsley-maxlength="254"
                   required>
		</div>

        <div class="form-group">
            <label for="username_from_email"><?php echo $this->lang->line('add_member_username_from_email'); ?></label>
            <div class="app-checkbox pull-left mg-b-5">
                <label class="pd-r-10">
                    <input type="checkbox" name="username_from_email" id="username_from_email" class="js-username-from-email form_control">
                    <span class="fa fa-check"></span>
                </label>
            </div>
        </div>

		<div class="js-username-from-email-target form-group">
			<label for="username"><?php echo $this->lang->line('add_member_username'); ?></label>
			<input type="text" name="username" id="username" class="form-control"
                   value="<?php echo $this->session->flashdata('username'); ?>"
                   data-parsley-maxlength="24"
                   data-parsley-required>
		</div>

        <div class="form-group">
            <label for="inform_member"><?php echo $this->lang->line('add_member_inform_member'); ?></label>
            <div class="app-checkbox pull-left mg-b-5">
                <label class="pd-r-10">
                    <input type="checkbox" name="inform_member" id="inform_member" checked="checked" class="form_control">
                    <span class="fa fa-check"></span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" name="add_member_submit" id="add_member_submit"
                    class="add_member_submit btn btn-primary btn-lg"
                    data-loading-text="<?php echo $this->lang->line('add_member_loading_text'); ?>">
                <i class="fa fa-user-plus pd-r-5"></i> <?php echo $this->lang->line('add_member'); ?>
            </button>
        </div>
	</div>

	<div class="col-sm-6">
        <div class="form-group">
            <label><?php echo $this->lang->line('roles_title'); ?></label>
            <?php foreach($roles as $role) {?>
            <div class="app-checkbox">
                <label class="pd-r-10">
                    <input type="checkbox" name="roles[]" value="<?php echo $role->role_id; ?>">
                    <span class="fa fa-check"></span> <?php echo $role->role_name; ?>
                </label>
            </div>
            <?php } ?>
        </div>

        <div class="form-group">
            <div class="btn-group js-password-btn-group" role="group">
                <a href="javascript:" class="btn btn-default js-genWordsButton"><?php echo $this->lang->line('button_generate'); ?></a>
                <a href="javascript:" class="btn btn-default js-show-pwd"><?php echo $this->lang->line('button_show'); ?></a>
            </div>
        </div>

		<div class="form-group">
			<label for="password"><?php echo $this->lang->line('add_member_password'); ?></label>
			<input type="password" name="password" id="password" class="form-control"
                   data-parsley-maxlength="255"
                   required>
		</div>

		<div class="form-group">
			<label for="password_confirm"><?php echo $this->lang->line('add_member_password_confirm'); ?></label>
			<input type="password" name="password_confirm" id="password_confirm" class="form-control"
                   data-parsley-maxlength="255"
                   required>
		</div>

	</div>
</div>
<?php echo form_close();