<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<?php echo form_open('adminpanel/contact_member/send_message', array('id' => "member_send_msg", 'autocomplete' => "off", 'class' => 'js-parsley', 'data-parsley-submit' => 'member_send_msg_submit')); ?>

<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
        <div class="form-group">
            <label for="message"><?php echo $this->lang->line('contact_member_message') . $email; ?></label>
            <textarea name="message" id="message" class="form-control"
                      data-parsley-trigger="change keyup"
                      required></textarea>
        </div>

        <div class="form-group">
            <button type="submit" name="member_send_msg_submit" id="member_send_msg_submit"
                    class="member_send_msg_submit btn btn-primary btn-lg"
                    data-loading-text="<?php echo $this->lang->line('contact_member_loading_text'); ?>">
                <i class="fa fa-send-o pd-r-5"></i> <?php echo $this->lang->line('contact_member_button'); ?>
            </button>
            <input type="hidden" name="user_id" value="<?php echo $this->uri->segment(3); ?>">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
        </div>
    </div>
</div>

<?php echo form_close(); ?>