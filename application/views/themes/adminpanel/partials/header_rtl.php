<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<header class="navbar header" role="menu">


    <a id="js-extramenu" href="javascript:" class="btn navbar-btn pull-left">
        <i class="fa fa-sticky-note-o"></i>
    </a>

    <ul class="nav navbar-nav navbar-nav-member mg-r-5 pull-left">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <?php
                if ($this->session->userdata('profile_img') != MEMBERS_GENERIC) {
                    ?>
                    <img class="thumbnail-wrapper-navbar" src="<?php echo base_url(); ?>assets/img/members/<?php echo $this->session->userdata('username'); ?>/<?php echo $this->session->userdata('profile_img'); ?>">
                <?php }else{ ?>
                    <img class="thumbnail-wrapper-navbar" src="<?php echo base_url(); ?>assets/img/members/<?php echo MEMBERS_GENERIC; ?>">
                <?php } ?>
            </a>
            <div class="dropdown-menu pull-left">
                <?php $this->load->view('themes/adminpanel/partials/dropdown-menu-member'); ?>
            </div>
        </li>
    </ul>

    <div class="navbar-header pull-right">
        <a class="navbar-brand block" href="javascript:">
            <img class="navbar-brand-logo" src="<?php echo base_url(); ?>assets/img/public/logo-header.png" alt="logo">
            <div class="navbar-brand-title">
                <span class="navbar-brand-title-small">CIMembership <small class="f700"><em>v<?php echo Settings_model::$db_config['cim_version']; ?></em></small></span>
            </div>
        </a>
    </div>

    <a id="js-showhide-menu" href="javascript:" class="btn navbar-btn pull-right">
        <i class="fa fa-eye-slash"></i>
    </a>

    <a id="js-narrow-menu" href="javascript:" class="btn navbar-btn pull-right">
        <i class="fa fa-bars"></i>
    </a>


    <div class="collapse navbar-collapse pd-0 pull-right">

        <div class="nav navbar-nav mg-l-5 navbar-search pull-right">
            <form action="javascript:" class="navbar-form">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-navbar" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
        </div>

        <ul class="nav navbar-nav mg-l-5">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Activity <i class="fa fa-list pd-l-5"></i></a>
                <div class="dropdown-menu dropdown-menu-300">

                    <h4 class="mg-0 pd-10 text-uppercase bg-primary">Messages</h4>

                    <div class="scroll pd-10">
                        <a href="javascript:" class="block pd-t-5 pd-b-5">
                            <strong>John</strong> <small>2 min ago</small><br>I finished the work on the login section of ...
                        </a>
                        <hr class="mg-0">
                        <a href="javascript:" class="block pd-t-5 pd-b-5">
                            <strong>Helga</strong> <small>15 min ago</small><br>Our meeting has been moved ot a new date ...
                        </a>
                        <hr class="mg-0">
                        <a href="javascript:" class="block pd-t-5 pd-b-5">
                            <strong>Chelsea</strong> <small>18 min ago</small><br>Order your sandwiches via our new internal app ...
                        </a>
                        <hr class="mg-0">
                        <a href="javascript:" class="block pd-t-5 pd-b-5">
                            <strong>[ICT] Kurt</strong> <small>22 min ago</small><br>We have 15 new systems for your department and ...
                        </a>
                        <hr class="mg-0">
                        <a href="javascript:" class="block pd-t-5 pd-b-5">
                            <strong>Karen</strong> <small>26 min ago</small><br>Hello did you have time to check out those documents ...
                        </a>
                        <hr class="mg-0">
                        <a href="javascript:" class="block pd-t-5 pd-b-5">
                            <strong>George</strong> <small>32 min ago</small><br>I think we need to set a meeting with those new ...
                        </a>
                    </div>

                    <hr class="mg-0">

                    <div class="row text-center tbl">
                        <a href="javascript:" class="col-xs-6 pd-5 btn btn-primary">
                            <small><i class="fa fa-history"></i> HISTORY</small>
                        </a>
                        <a href="javascript:" class="col-xs-6 pd-5 btn btn-primary">
                            <small><i class="fa fa-check"></i> MARK ALL READ</small>
                        </a>
                    </div>
                </div>
            </li>
        </ul>

    </div>

</header>