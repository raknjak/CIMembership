<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo PHP_EOL . PHP_EOL; ?>
<?php echo $this->lang->line('register_email_activation_message_txt'); ?>
<?php echo PHP_EOL . PHP_EOL; ?>
<?php echo base_url() . "activate_account/check/" . urlencode($email) . "/" . $cookie_part; ?>
<?php echo PHP_EOL . PHP_EOL; ?>
<?php
if (Settings_model::$db_config['registration_approval_required']) { ?>
    <?php echo $this->lang->line('register_email_approve_success'); ?>
    <?php print PHP_EOL; ?><?php print PHP_EOL; ?>
<?php } ?>
