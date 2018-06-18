<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<br><br>
<?php echo $this->lang->line('add_member_email_message'); ?>
<br><br>
<?php echo base_url() ."auth/activate_account/check/". urlencode($this->input->post('email')) ."/". $cookie_part; ?>
<br><br>