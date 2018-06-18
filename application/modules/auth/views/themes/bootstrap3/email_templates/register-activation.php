<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<br><br>
<?php echo $this->lang->line('register_email_activation_message'); ?>
<br><br>
<a href="<?php echo base_url() . "activate_account/check/" . urlencode($email) . "/" . $cookie_part; ?>"><?php echo $this->lang->line('register_email_activation_link'); ?></a>
<br><br>
<?php
if (Settings_model::$db_config['registration_approval_required']) { ?>
    <?php echo $this->lang->line('register_email_approve_success'); ?>
    <br><br>
<?php } ?>