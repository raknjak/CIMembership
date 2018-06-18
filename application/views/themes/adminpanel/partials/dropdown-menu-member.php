<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="dropdown-menu-member">

    <div class="pd-15 f700 text-uppercase text-center" style="word-wrap: break-word">
        <?php echo $this->session->userdata('username'); ?>
    </div>

    <hr class="mg-0">

    <a href="<?php echo base_url(); ?>membership/my_dashboard" class="block bg-white bg-darken pd-10">
        <i class="fa fa-dashboard pd-l-10 pd-r-10"></i> Dashboard
    </a>

    <a href="<?php echo base_url(); ?>membership/profile" class="block bg-white bg-darken pd-10">
        <i class="fa fa-user pd-l-10 pd-r-10"></i> Profile
    </a>

    <!--a href="<?php echo base_url(); ?>messenger/messages" class="block bg-white bg-darken pd-10">
        <i class="fa fa-comments-o pd-l-10 pd-r-10"></i> Messages <!--span class="label label-success pull-right" style="position:relative; top: 3px;">0</span>
    </a-->

    <a href="<?php echo base_url(); ?>logout" class="block bg-success bg-darken fg-white pd-10">
        <i class="fa fa-power-off pd-l-10 pd-r-10"></i> Log out
    </a>
</div>