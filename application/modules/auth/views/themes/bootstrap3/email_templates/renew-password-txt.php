<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo PHP_EOL . PHP_EOL; ?>
<?php echo $this->lang->line('renew_password_message_txt'); ?>
<?php echo PHP_EOL . PHP_EOL; ?>
<?php echo base_url() ."new_password/". urlencode($email) ."/". $token; ?>
<?php echo PHP_EOL . PHP_EOL; ?>