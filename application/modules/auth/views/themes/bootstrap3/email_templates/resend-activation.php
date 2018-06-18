<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<br><br>
<?php echo $this->lang->line('resend_activation_message'); ?>
<br><br>
<a href="<?php echo base_url() ."activate_account/check/". urlencode($email) ."/". $cookie_part; ?>"><?php echo $this->lang->line('resend_activation_link'); ?></a>
<br><br>