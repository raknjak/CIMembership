<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo PHP_EOL . PHP_EOL; ?>
<?php echo $this->lang->line('profile_email_reactivate_txt'); ?>
<?php echo PHP_EOL; ?>
<?php echo base_url() . "activate_account/check/". urlencode($email) ."/". $cookie_part; ?>
<?php echo PHP_EOL . PHP_EOL; ?>