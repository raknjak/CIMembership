<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<h2><?php echo $this->lang->line('role_add_title'); ?></h2>

<?php echo form_open('adminpanel/roles/add_role', array('id' => 'add_role_form', 'class' => 'js-parsley mg-t-20', 'data-parsley-submit' => 'add_role_save')); ?>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="role_name"><?php echo $this->lang->line('role_name'); ?></label>
            <input type="text" name="role_name" id="role_name" class="form-control input-lg"
                   data-parsley-maxlength="50"
                   required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="role_description"><?php echo $this->lang->line('role_description'); ?></label>
            <textarea name="role_description" id="role_description" class="form-control"
                  data-parsley-maxlength="255"></textarea>
        </div>
    </div>
</div>

<div class="form-group">
    <button type="submit"
            class="add_role_save btn btn-primary btn-lg"
            data-loading-text="<?php echo $this->lang->line('roles_loading_text'); ?>">
        <i class="fa fa-plus pd-r-5"></i> <?php echo $this->lang->line('roles_button_create'); ?>
    </button>
</div>

<?php echo form_close(); ?>

<hr>

<h2><?php echo $this->lang->line('role_manage'); ?></h2>

<p class="f700 mg-b-20">
    <?php echo $this->lang->line('roles_warning'); ?>
</p>

<?php foreach ($roles as $role_id => $role) { ?>

    <div class="bg-white pd-15 mg-b-20">

        <?php echo form_open('adminpanel/roles/roles_multi', 'id="roles_form_'. $role_id .'" class="mg-b-5"'); ?>

        <h4 class="f700 text-uppercase"><?php echo $role['role_name']; ?></h4>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group mg-b-0">
                    <label for="role_name"><?php echo $this->lang->line('role_name'); ?></label>
                    <input type="text" name="role_name" id="role_name_<?php echo $role_id; ?>" value="<?php echo $role['role_name']; ?>" class="form-control"
                           data-parsley-maxlength="50"
                           required>

                    <div class="mg-t-5">

                        <a class="btn btn-primary collapsed" role="button" data-toggle="collapse" href="#rolesCollapse_<?php echo $role_id; ?>" aria-expanded="false" aria-controls="rolesCollapse_<?php echo $role_id; ?>">
                            <i class="fa fa-users pd-r-5"></i> <?php echo $this->lang->line('roles_btn_toggle'); ?>
                        </a>

                        <div class="btn-group" role="group">
                            <button type="submit" name="save" class="btn btn-info" data-loading-text="<?php echo $this->lang->line('roles_update_text'); ?>"><i class="fa fa-refresh pd-r-5"></i> <?php echo $this->lang->line('roles_btn_save'); ?></button>
                            <button type="submit" name="delete" class="btn btn-danger js-confirm-delete" data-loading-text="<?php echo $this->lang->line('roles_delete_text'); ?>"><i class="fa fa-trash-o pd-r-5"></i> <?php echo $this->lang->line('roles_btn_delete'); ?></button>
                        </div>

                        <input type="hidden" name="role_id" value="<?php echo $role_id; ?>">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="role_description"><?php echo $this->lang->line('role_description'); ?></label>
                    <textarea name="role_description" id="role_description_<?php echo $role_id; ?>" class="form-control"
                              data-parsley-maxlength="255"><?php echo $role['role_description']; ?></textarea>
                </div>
            </div>
        </div>

        <?php echo form_close(); ?>

        <div class="collapse" id="rolesCollapse_<?php echo $role_id; ?>" aria-expanded="false" style="height: 0;">
            <?php echo form_open('adminpanel/roles/save_role_permissions', 'id="save_role_permissions_form_'. $role_id .'"'); ?>

            <div class="form-group">
                <?php foreach ($role['permissions'] as $id => $permission) { ?>

                    <div class="app-checkbox">
                        <label class="pd-r-10">
                            <?php echo form_checkbox(array('name' => 'permissions[]', 'class' => '', 'value' => $id, 'checked' => ($permission['active'] == true ? true : false))); ?>
                            <span class="fa fa-check"></span> <?php echo $permission['description']; ?>
                        </label>
                    </div>

                <?php } ?>

            </div>

            <div class="form-group mg-b-0">
                <button type="submit" name="save_roles" class="btn btn-success" data-loading-text="<?php echo $this->lang->line('roles_save_text'); ?>"><i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('roles_btn_save_roles'); ?></button>
                <input type="hidden" name="role_id" value="<?php echo $role_id; ?>">
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>

<?php } ?>


