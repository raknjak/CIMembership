<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<br><br>
<?php echo $this->lang->line('renew_password_message'); ?>
<br><br>
<a href="<?php echo base_url() ."new_password/". urlencode($email) ."/". $token; ?>"><?php echo $this->lang->line('renew_password_link'); ?></a>
<br><br>