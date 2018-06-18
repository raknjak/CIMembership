<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<div>
    <?php $this->load->view('generic/flash_error'); ?>
</div>

<h2><?php echo $this->lang->line('profile_picture'); ?></h2>

<p>
    <em><?php echo sprintf($this->lang->line('profile_picture_max_size'), $picture_max_upload_size); ?></em>
</p>

<div id="dropzone" class="text-center text-uppercase bd-db-gray bd-5x mg-b-15 f700">

    <?php echo $this->lang->line('profile_drop_zone'); ?>

    <div id="files" class="files text-primary f700">
        <?php if (!isset($profile_image)) {
            echo $this->lang->line('profile_picture_not_present');
        } ?>
    </div>

    <?php if (isset($profile_image)) { ?>
        <div class="mg-t-10">
            <img class="js_profile_image profile-img img-thumbnail" src="<?php echo base_url(); ?>assets/img/members/<?php echo $this->session->userdata('username'); ?>/<?php echo $profile_image; ?>">
        </div>
    <?php }else{ ?>
        <div class="mg-t-10">
            <img class="js_profile_image profile-img img-thumbnail" src="<?php echo base_url(); ?>assets/img/members/<?php echo MEMBERS_GENERIC; ?>">
        </div>
    <?php } ?>
</div>

<span class="btn btn-success fileinput-button mg-b-10">
    <i class="fa fa-plus pd-r-5"></i>
    <span><?php echo $this->lang->line('profile_picture_select_button'); ?></span>
    <input id="fileupload" type="file" name="files[]" data-path="membership/profile/upload_profile_picture/<?php echo $this->session->userdata('username'); ?>">
    <input type="hidden" name="profile_username" id="profile_username" value="<?php echo $this->session->userdata('username'); ?>">
</span>

<div id="progress" class="progress hidden">
    <div class="progress-bar progress-bar-success"></div>
</div>

<?php echo form_open('membership/profile/delete_profile_picture', array('id' => 'delete_profile_picture')); ?>
<button id="delete_profile_picture_submit"
        name="delete_profile_picture_submit"
        class="btn btn-danger mg-t-10 mg-b-5"
        data-loading-text="<?php echo $this->lang->line('profile_picture_delete_button_loading_text'); ?>">
    <i class="fa fa-trash-o pd-r-5"></i> <?php echo $this->lang->line('profile_picture_delete_button'); ?>
</button>
<?php echo form_close(); ?>

<hr>

<div class="row">

	<div class="col-sm-6">

		<h2><?php echo $this->lang->line('profile_personal_details'); ?></h2>

		<?php echo form_open('membership/profile/update_account', array('id' => 'profile_form', 'class' => 'js-parsley', 'data-parsley-submit' => 'profile_submit')); ?>

        <?php if (Settings_model::$db_config['allow_username_change']) : ?>
            <div class="form-group">
                <label for="username"><?php echo $this->lang->line('profile_username'); ?></label>
                <input type="text" name="username" id="username" class="form-control input-lg" value="<?php echo $this->session->userdata('username'); ?>"
                       data-parsley-trigger="change keyup"
                       data-parsley-maxlength="24"
                       required>
            </div>
        <?php endif; ?>

		<div class="form-group">
			<label for="profile_first_name"><?php echo $this->lang->line('profile_first_name'); ?></label>
			<input type="text" name="first_name" id="profile_first_name" class="form-control input-lg" value="<?php echo $first_name; ?>"
				   data-parsley-trigger="change keyup"
                   data-parsley-maxlength="255"
				   required>
		</div>
		
		<div class="form-group">
			<label for="profile_last_name"><?php echo $this->lang->line('profile_last_name'); ?></label>
			<input type="text" name="last_name" id="profile_last_name"  class="form-control input-lg" value="<?php echo $last_name; ?>"
				   data-parsley-trigger="change keyup"
				   required>
		</div>
		
		<div class="form-group">
			<label for="profile_email"><?php echo $this->lang->line('profile_change_email'); ?></label>
			<input type="text" name="email" id="profile_email" class="form-control input-lg" value="<?php echo $email; ?>"
				   data-parsley-trigger="change keyup"
                   data-parsley-maxlength="254"
				   data-parsley-type="email"
				   required>
		</div>

		<div class="form-group">
			<button type="submit" name="profile_submit" id="profile_submit" class="profile_submit btn btn-primary btn-lg"
					data-loading-text="<?php echo $this->lang->line('update_profile_button_loading_text'); ?>">
				<i class="fa fa-user-plus pd-r-5"></i> <?php echo $this->lang->line('profile_button_update'); ?>
			</button>
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
		</div>

		<?php echo form_close(); ?>
	</div>

	<div class="col-sm-6">
		<?php echo form_open('membership/profile/update_password', array('id' => 'profile_pwd_form', 'class' => 'js-parsley', 'data-parsley-submit' => 'profile_pwd_submit')); ?>

		<h2>
			<?php echo  $this->lang->line('profile_edit_password'); ?>
		</h2>

		<?php if ($this->session->flashdata('pwd_error')) { ?>
			<div id="pwd_error">
				<div class="alert alert-danger">
					<h4><?php echo $this->lang->line('profile_password_error'); ?></h4>
					<p><?php echo $this->session->flashdata('pwd_error'); ?></p>
				</div>
			</div>
		<?php } ?>

		<?php if ($this->session->flashdata('pwd_success')) { ?>
			<div id="success">
				<div class="alert alert-success">
					<h4><?php echo $this->lang->line('profile_password_success'); ?></h4>
					<p><?php echo $this->session->flashdata('pwd_success'); ?></p>
				</div>
			</div>
		<?php } ?>

		<div class="form-group pd-10 bg-info fg-white text-center f700">
			<?php echo $this->lang->line('profile_password_warning'); ?>
		</div>

		<div class="form-group">
			<div class="btn-group js-password-btn-group" role="group" aria-label="...">
				<a href="javascript:" class="btn btn-default js-genWordsButton"><?php echo $this->lang->line('button_generate'); ?></a>
				<a href="javascript:" class="btn btn-default js-show-pwd"><?php echo $this->lang->line('button_show'); ?></a>
			</div>
		</div>

		<div class="form-group">
			<label for="current_password"><?php echo $this->lang->line('profile_current_password'); ?></label>
			<input type="password" name="current_password" id="current_password" class="form-control"
				   data-parsley-trigger="change keyup"
				   required>
		</div>

		<div class="form-group">
			<label for="profile_new_password"><?php echo $this->lang->line('profile_new_password'); ?></label>
			<input type="password" name="password" id="password" class="form-control"
				   data-parsley-trigger="change keyup"
				   required>
		</div>

		<div class="form-group">
			<label for="password_confirm"><?php echo $this->lang->line('profile_new_password_repeat'); ?></label>
			<input type="password" name="password_confirm" id="password_confirm" class="form-control"
				   data-parsley-trigger="change keyup"
				   required>
		</div>

		<div class="form-group">
			<div class="app-checkbox">
				<label class="pd-r-10">
					<?php echo form_checkbox(array('name' => 'send_copy', 'value' => 'accept', 'checked' => false, 'class' => 'checkbox')); ?>
					<span class="fa fa-check pd-r-5"></span> <?php echo $this->lang->line('profile_send_copy_to_email'); ?>
				</label>
			</div>
		</div>

		<button type="submit" name="profile_pwd_submit" id="profile_pwd_submit" class="profile_pwd_submit btn btn-primary btn-lg"
                data-loading-text="<?php echo $this->lang->line('profile_update_password_loading_text'); ?>">
			<i class="fa fa-key pd-r-5"></i> <?php echo $this->lang->line('profile_update_password'); ?>
		</button>

        <?php echo form_hidden('email', $email); ?>

		<?php echo form_close(); ?>
	</div>
	
</div>

<hr>

<?php echo form_open('membership/profile/delete_account', array('id' => 'delete_profile_form')); ?>

	<div class="form-group">
		<strong><?php echo $this->lang->line('profile_perma_delete_account'); ?></strong>
		<br>
		<button type="submit" id="permanently_remove" class="btn btn-danger btn-lg js-confirm-delete">
			<i class="fa fa-trash-o pd-r-5"></i> <?php echo $this->lang->line('profile_button_delete_account'); ?>
		</button>
	</div>

<?php echo form_close(); ?>


