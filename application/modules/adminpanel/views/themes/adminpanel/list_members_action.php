<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="mg-b-10">
    <strong><?php echo $this->lang->line('list_members_action_title'); ?></strong><br>
    <div class="btn-group pd-r-5 pull-left" role="group">
        <button type="button" name="delete" id="delete" class="btn btn-primary" data-title="<?php echo $this->lang->line('list_members_delete'); ?>"><i class="fa fa-trash-o"></i></button>
    </div>
    <div class="btn-group pd-r-5 pull-left" role="group">
        <button type="button" name="activate" id="activate" class="btn btn-primary" data-title="<?php echo $this->lang->line('list_members_activate'); ?>"><i class="fa fa-check"></i></button>
        <button type="button" name="deactivate" id="deactivate" class="btn btn-primary" data-title="<?php echo $this->lang->line('list_members_deactivate'); ?>"><i class="fa fa-times"></i></button>
    </div>
    <?php if (Settings_model::$db_config['registration_approval_required']) : ?>
    <div class="btn-group pd-r-5 pull-left" role="group">
        <button type="button" name="approved" id="approved" class="btn btn-primary" data-title="<?php echo $this->lang->line('list_members_approve'); ?>"><i class="fa fa-thumbs-o-up"></i></button>
        <button type="button" name="unapproved" id="unapproved" class="btn btn-primary" data-title="<?php echo $this->lang->line('list_members_unapprove'); ?>"><i class="fa fa-thumbs-o-down"></i></button>
    </div>
    <?php endif; ?>
    <div class="btn-group" role="group">
        <button type="button" name="ban" id="ban" class="btn btn-primary" data-title="<?php echo $this->lang->line('list_members_ban'); ?>"><i class="fa fa-lock"></i></button>
        <button type="button" name="unban" id="unban" class="btn btn-primary" data-title="<?php echo $this->lang->line('list_members_unban'); ?>"><i class="fa fa-unlock"></i></button>
    </div>
</div>