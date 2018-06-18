<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<ul class="list-unstyled">
    <li><a href="<?php echo base_url(); ?>renew_password"><?php echo $this->lang->line('auth_renew'); ?></a></li>
    <li><a href="<?php echo base_url(); ?>retrieve_username"><?php echo $this->lang->line('auth_retrieve'); ?></a></li>
    <li><a href="<?php echo base_url(); ?>resend_activation"><?php echo $this->lang->line('auth_resend'); ?></a></li>
</ul>