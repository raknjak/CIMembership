<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="extra-content">
    <h4 class="pd-l-15 overflow-off"><a href="<?php echo base_url(); ?>logout"><i class="fa fa-power-off fg-danger"></i></a></h4>
    <p class="text-primary hidden-folded pd-l-15">
        Logged in as:<br><strong><?php echo $this->session->userdata('username'); ?></strong>
    </p>
</div>