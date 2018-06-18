<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<h2 class="fg-primary mg-t-0 f700">
    <?php echo $this->lang->line('member_detail_viewing_member'); ?>:
    <strong><?php echo $member->username; ?> (ID: <?php echo $member->user_id; ?>)</strong>
    <?php if (Settings_model::$db_config['allow_username_change']) : ?>
        <small><a href="javascript:" class="js-username-history fg-inverse fg-lighten" data-userid="<?php echo $member->user_id; ?>">
                <?php echo $this->lang->line('member_detail_view_username_history'); ?>
            </a></small>
    <?php endif; ?>
</h2>

<div class="panel card bd-0">
    <div class="panel-body bg-primary text-center pd-0">
        <div class="row tbl">
            <div class="col-xs-6 bd-white-right pd-15">
                <h4 class="mg-t-0 f700"><?php echo $this->lang->line('last_login'); ?></h4>
                <strong><?php echo $member->last_login; ?></strong>
            </div>
            <div class="col-xs-6 pd-15 rd-r">
                <h4 class="mg-t-0 f700"><?php echo $this->lang->line('member_detail_date_registered'); ?></h4>
                <strong><?php echo $member->date_registered; ?></strong>
            </div>
        </div>
    </div>
</div>

<p>
    <em><?php echo sprintf($this->lang->line('member_detail_picture_max_size'), $picture_max_upload_size); ?></em>
</p>

<div id="dropzone" class="text-center text-uppercase bd-db-gray bd-5x mg-b-15 f700">
    <?php echo $this->lang->line('member_detail_drop_zone'); ?>

    <div id="files" class="files text-primary f700">
        <?php if (!isset($profile_image)) {
            echo $this->lang->line('member_detail_picture_not_present');
        } ?>
    </div>

    <?php if (isset($profile_image)) { ?>
        <div class="mg-t-10">
            <img class="js_profile_image profile-img img-thumbnail" src="<?php echo base_url(); ?>assets/img/members/<?php echo $member->username; ?>/<?php echo $profile_image; ?>">
        </div>
    <?php }else{ ?>
        <div class="mg-t-10">
            <img class="js_profile_image profile-img img-thumbnail" src="<?php echo base_url(); ?>assets/img/members/<?php echo MEMBERS_GENERIC; ?>">
        </div>
    <?php } ?>
</div>

<span class="btn btn-success fileinput-button mg-b-10">
    <i class="fa fa-plus pd-r-5"></i>
    <span><?php echo $this->lang->line('member_detail_picture_select_button'); ?></span>
    <input id="fileupload" type="file" name="files[]" data-path="adminpanel/member_detail/upload_profile_picture/<?php echo $member->username; ?>">
    <input type="hidden" name="profile_username" id="profile_username" value="<?php echo $member->username; ?>">
</span>

<div id="progress" class="progress hidden">
    <div class="progress-bar progress-bar-success"></div>
</div>

<?php echo form_open('adminpanel/member_detail/delete_profile_picture/'. $member->username .'/'. $member->user_id, array('id' => 'delete_profile_picture')); ?>
<button id="delete_profile_picture_submit" name="delete_profile_picture_submit" class="btn btn-danger mg-t-10 mg-b-5" data-loading-text="<?php echo $this->lang->line('member_detail_picture_delete_loading_text'); ?>">
    <i class="fa fa-trash-o pd-r-5"></i> <?php echo $this->lang->line('member_detail_picture_delete_button'); ?>
</button>
<?php echo form_close(); ?>

<hr>

<div class="row mg-t-20">

    <?php echo form_open('adminpanel/member_detail/save', array('id' => 'save_member_form', 'autocomplete' => 'off', 'class' => 'js-parsley', 'data-parsley-submit' => 'save_member')); ?>

    <div class="col-sm-6">

        <div class="js-username-from-email-target form-group">
            <?php if (Settings_model::$db_config['root_admin_username'] == $member->username) { ?>
            <div><i class="fa fa-star fg-warning"></i> <?php echo $this->lang->line('member_detail_root_admin_text'); ?></div>
            <?php } ?>
            <label for="username"><?php echo $this->lang->line('member_detail_username'); ?></label>
            <input type="text" name="username" id="username" value="<?php echo $member->username; ?>"
                   class="form-control"
                   required>
        </div>

        <div class="form-group">
            <label for="email"><?php echo $this->lang->line('member_detail_email_address'); ?></label>
            <input type="text" name="email" id="email" value="<?php echo $member->email; ?>"
                   class="form-control"
                   required>
        </div>

        <div class="form-group">
            <label for="username_from_email"><?php echo $this->lang->line('member_detail_username_from_email'); ?></label>
            <div class="app-checkbox pull-left mg-b-5">
                <label class="pd-r-10">
                    <input type="checkbox" name="username_from_email" id="username_from_email" value="accept" class="js-username-from-email form_control">
                    <span class="fa fa-check"></span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="first_name"><?php echo $this->lang->line('member_detail_first_name'); ?></label>
            <input type="text" name="first_name" id="first_name" value="<?php echo $member->first_name; ?>"
                   class="form-control">
        </div>

        <div class="form-group">
            <label for="last_name"><?php echo $this->lang->line('member_detail_last_name'); ?></label>
            <input type="text" name="last_name" id="last_name" value="<?php echo $member->last_name; ?>"
                   class="form-control">
        </div>

        <div class="form-group">
            <label for="send_copy"><?php echo $this->lang->line('member_detail_send_copy'); ?></label>
            <div class="app-checkbox pull-left mg-b-5">
                <label class="pd-r-10">
                    <input type="checkbox" name="send_copy" id="send_copy" value="accept" class="form_control">
                    <span class="fa fa-check"></span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="save_member btn btn-primary btn-lg" data-loading-text="<?php echo $this->lang->line('member_detail_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('member_detail_save'); ?>
            </button>
            <input type="hidden" name="user_id" value="<?php echo $member->user_id; ?>">
            <input type="hidden" name="old_username" value="<?php echo $member->username; ?>">
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <label><?php echo $this->lang->line('roles_title'); ?></label>
            <?php foreach($roles as $role) {?>
                <div class="app-checkbox">
                    <label class="pd-r-10">
                        <input type="checkbox" name="roles[]" value="<?php echo $role->role_id; ?>"<?php foreach($member_roles as $mr) {if ($role->role_id == $mr->role_id) {echo ' checked="checked"';}}; ?>>
                        <span class="fa fa-check"></span> <?php echo $role->role_name; ?>
                    </label>
                </div>
            <?php } ?>
        </div>

        <div class="form-group">
            <label for="banned"><?php echo $this->lang->line('banned'); ?>?</label>
            <select name="banned" id="banned" class="form-control">
                <option value="0"<?php echo ($member->banned == false ? ' selected="selected"' : ''); ?>><?php echo $this->lang->line('no'); ?></option>
                <option value="1"<?php echo ($member->banned == true ? ' selected="selected"' : ''); ?>><?php echo $this->lang->line('yes'); ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="active"><?php echo $this->lang->line('activated'); ?>?</label>
            <select name="active" id="active" class="form-control">
                <option value="1"<?php echo ($member->active == true ? ' selected="selected"' : ''); ?>><?php echo $this->lang->line('yes'); ?></option>
                <option value="0"<?php echo ($member->active == false ? ' selected="selected"' : ''); ?>><?php echo $this->lang->line('no'); ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="password"><?php echo $this->lang->line('member_detail_new_password'); ?></label>
            <input type="password" name="password" id="password"
                   data-parsley-maxlength="255"
                   class="form-control">
        </div>

        <div class="form-group">
            <div class="btn-group js-password-btn-group" role="group" aria-label="...">
                <a href="javascript:" class="btn btn-default js-genWordsButton"><?php echo $this->lang->line('button_generate'); ?></a>
                <a href="javascript:" class="btn btn-default js-show-pwd"><?php echo $this->lang->line('button_show'); ?></a>
            </div>
        </div>

    </div>

    <?php echo form_close(); ?>

</div>