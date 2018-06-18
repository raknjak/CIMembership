<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo PHP_EOL . PHP_EOL; ?>
<?php echo $this->lang->line('add_member_email_message_txt'); ?>
<?php echo PHP_EOL . PHP_EOL; ?>
<?php echo base_url() ."auth/activate_account/check/". urlencode($this->input->post('email')) ."/". $cookie_part; ?>
<?php echo PHP_EOL . PHP_EOL; ?>