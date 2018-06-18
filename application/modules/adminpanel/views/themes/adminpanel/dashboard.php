<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>


<div class="row text-center">
    <div class="col-sm-6 col-md-3">
        <div class="panel card bd-0">
            <div class="panel-body bg-primary">
                <h4><?php echo $this->lang->line('dash_new_members_week'); ?></h4>
            </div>
            <div class="panel-body bg-white">
                <h3 class="mg-0 f700"><?php echo number_format($new_week); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="panel card bd-0">
            <div class="panel-body bg-primary">
                <h4><?php echo $this->lang->line('dash_new_members_month'); ?></h4>
            </div>
            <div class="panel-body bg-white">
                <h3 class="mg-0 f700"><?php echo number_format($new_month); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="panel card bd-0">
            <div class="panel-body bg-primary">
                <h4><?php echo $this->lang->line('dash_new_members_year'); ?></h4>
            </div>
            <div class="panel-body bg-white">
                <h3 class="mg-0 f700"><?php echo number_format($new_year); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="panel card bd-0">
            <div class="panel-body bg-primary">
                <h4><?php echo $this->lang->line('dash_total_members'); ?></h4>
            </div>
            <div class="panel-body bg-white">
                <h3 class="mg-0 f700"><?php echo number_format($total_users); ?></h3>
            </div>
        </div>
    </div>
</div>

<h2><?php echo $this->lang->line('dash_latest_members_title'); ?></h2>

<div class="panel card bd-0">
    <div class="panel-body bg-white">
        <div class="table-responsive">
            <table class="table table-hover table-list-members">
                <thead>

                <tr>
                    <th>id</th>
                    <th><?php echo $this->lang->line('dashboard_username'); ?></th>
                    <th><?php echo $this->lang->line('dashboard_first_name'); ?></th>
                    <th><?php echo $this->lang->line('dashboard_last_name'); ?></th>
                    <th><?php echo $this->lang->line('dashboard_email_address'); ?></th>
                    <th class="text-center"><?php echo $this->lang->line('dashboard_active'); ?></th>
                    <th><?php echo $this->lang->line('dashboard_last_login'); ?></th>
                    <th><?php echo $this->lang->line('dashboard_date_registered'); ?></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($latest_members as $member) { ?>
                    <tr>
                        <td><?php echo $member->user_id; ?></td>
                        <td>
                            <a href="<?php echo base_url(); ?>adminpanel/member_detail/<?php echo $member->user_id; ?>">
                                <?php if ($member->profile_img != MEMBERS_GENERIC) { ?>
                                    <img class="thumbnail-wrapper" src="<?php echo base_url(); ?>assets/img/members/<?php echo $member->username ."/". $member->profile_img; ?>">
                                <?php }else{ ?>
                                    <img class="thumbnail-wrapper" src="<?php echo base_url(); ?>assets/img/members/<?php echo MEMBERS_GENERIC; ?>">
                                <?php }
                                if ($member->username == Settings_model::$db_config['root_admin_username']): ?>
                                    <i class="fa fa-star fg-warning"></i>
                                <?php endif; ?>
                                <?php echo $member->username; ?>
                            </a>
                        </td>
                        <td><?php echo $member->first_name; ?></td>
                        <td><?php echo $member->last_name; ?></td>
                        <td><?php echo $member->email; ?></td>
                        <td class="text-center"><i class="fa <?php echo $member->active == 1 ? ' fa-check fg-success' : 'fa-times fg-danger'; ?>"></i></td>
                        <td><?php echo date('jS \o\f F Y', strtotime($member->last_login)); ?></td>
                        <td><?php echo date('jS \o\f F Y', strtotime($member->date_registered)); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<h2>Static stuff (not translated) <small>(will be replaced with better stuff in the future)</small></h2>

<p>
    We don't have any connection to the database on the examples below, all values are hard-coded. As more new features are added we will
    modify this page - your suggestions are appreciated as always.
</p>

<div class="row">

    <div class="col-sm-6">

        <div class="panel card bd-0">

            <div class="panel-body bg-white text-center pd-0">

                <div class="row tbl">
                    <div class="col-xs-6 bd-light-gray-right">
                        <div class="row tbl">
                            <div class="col-xs-4 pd-0">
                                <i class="fa fa-star fa-2x pd-t-5 fg-warning"></i>
                            </div>
                            <div class="col-xs-8">
                                <h5 class="mg-t-0">STARS</h5>
                                <h4 class="mg-0 fg-gray"><strong>5,685</strong></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 pd-15">
                        <div class="row tbl">
                            <div class="col-xs-4 pd-0">
                                <i class="fa fa-flag fa-2x pd-t-5 fg-success"></i>
                            </div>
                            <div class="col-xs-8">
                                <h5 class="mg-t-0">FLAGS</h5>
                                <h4 class="mg-0 fg-gray"><strong>1,269</strong></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="hr-light-gray mg-0">

                <div class="row tbl">
                    <div class="col-xs-6 bd-light-gray-right">
                        <div class="row tbl">
                            <div class="col-xs-4 pd-0">
                                <i class="fa fa-share-alt fa-2x pd-t-5 fg-danger"></i>
                            </div>
                            <div class="col-xs-8">
                                <h5 class="mg-t-0">SHARED</h5>
                                <h4 class="mg-0 fg-gray"><strong>18,474</strong></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 pd-15">
                        <div class="row tbl">
                            <div class="col-xs-4 pd-0">
                                <i class="fa fa-comments fa-2x pd-t-5 fg-info"></i>
                            </div>
                            <div class="col-xs-8">
                                <h5 class="mg-t-0">COMMENTS</h5>
                                <h4 class="mg-0 fg-gray"><strong>86,910</strong></h4>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="col-sm-6">

        <div class="panel card bd-0">

            <div class="panel-body bg-white text-center pd-0">

                <div class="row tbl">
                    <div class="col-xs-6 bd-light-gray-right">
                        <div class="row tbl">
                            <div class="col-xs-8">
                                <h5 class="mg-t-0">SALES</h5>
                                <h4 class="mg-0 fg-gray"><strong>7,922</strong></h4>
                            </div>
                            <div class="col-xs-4 pd-0">
                                <i class="fa fa-money fa-2x pd-t-5 fg-success"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 pd-15">
                        <div class="row tbl">
                            <div class="col-xs-8">
                                <h5 class="mg-t-0">DRINKS</h5>
                                <h4 class="mg-0 fg-gray"><strong>7,838</strong></h4>
                            </div>
                            <div class="col-xs-4 pd-0">
                                <i class="fa fa-glass fa-2x pd-t-5 fg-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="hr-light-gray mg-0">

                <div class="row tbl">
                    <div class="col-xs-6 bd-light-gray-right">
                        <div class="row tbl">
                            <div class="col-xs-8">
                                <h5 class="mg-t-0">EVENTS</h5>
                                <h4 class="mg-0 fg-gray"><strong>561</strong></h4>
                            </div>
                            <div class="col-xs-4 pd-0">
                                <i class="fa fa-magic fa-2x pd-t-5 fg-inverse"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 pd-15">
                        <div class="row tbl">
                            <div class="col-xs-8">
                                <h5 class="mg-t-0">PARTS</h5>
                                <h4 class="mg-0 fg-gray"><strong>166</strong></h4>
                            </div>
                            <div class="col-xs-4 pd-0">
                                <i class="fa fa-cubes fa-2x pd-t-5 fg-gray"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<div class="row">
    <div class="col-sm-3">
        <div class="panel card bd-0">
            <a href="javascript:" class="panel-body bg-white fg-info fg-darken pd-0">
                <div class="row tbl">
                    <div class="col-xs-4 pd-15">
                                <span class="icon icon-2x round bg-info bg-darken fg-white bd-0">
                                    <i class="fa fa-share-alt fa-2x"></i>
                                </span>
                    </div>
                    <div class="col-xs-8 pd-15 text-right">
                        <h2 class="mg-0 300">2,568</h2>
                        <span class="sml fg-gray fg-darken">CONNECTIONS</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="panel card bd-0">
            <a href="javascript:" class="panel-body bg-white fg-success fg-darken pd-0">
                <div class="row tbl">
                    <div class="col-xs-4 pd-15">
                                <span class="icon icon-2x round bg-success bg-darken fg-white bd-0">
                                    <i class="fa fa-briefcase fa-2x"></i>
                                </span>
                    </div>
                    <div class="col-xs-8 pd-15 text-right">
                        <h2 class="mg-0 300">86</h2>
                        <span class="sml fg-gray fg-darken">PROJECTS</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="panel card bd-0">
            <a href="javascript:" class="panel-body bg-white fg-warning fg-darken pd-0">
                <div class="row tbl">
                    <div class="col-xs-4 pd-15">
                                <span class="icon icon-2x round bg-warning bg-darken fg-white bd-0">
                                    <i class="fa fa-tasks fa-2x"></i>
                                </span>
                    </div>
                    <div class="col-xs-8 pd-15 text-right">
                        <h2 class="mg-0 300">7,506</h2>
                        <span class="sml fg-gray fg-darken">TASKS</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="panel card bd-0">
            <a href="javascript:" class="panel-body bg-white fg-danger fg-darken pd-0">
                <div class="row tbl">
                    <div class="col-xs-4 pd-15">
                                <span class="icon icon-2x round bg-danger bg-darken fg-white bd-0">
                                    <i class="fa fa-paperclip fa-2x"></i>
                                </span>
                    </div>
                    <div class="col-xs-8 pd-15 text-right">
                        <h2 class="mg-0 300">953</h2>
                        <span class="sml fg-gray fg-darken">FILE SHARES</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="panel card bd-00">
            <a class="panel-body bg-danger bg-darken fg-white">
                <div class="row">
                    <div class="col-xs-7 text-left">
                        <h1 class="mg-0 300">23%</h1>
                        SERVER LOAD<br>
                        <hr class="hr-white opacity-2 mg-t-5 mg-b-5">
                        <small><i class="fa fa-check"></i> Uptime: 99.98%</small>
                    </div>
                    <div class="col-xs-5 text-right">
                        <i class="fa fa-area-chart fa-4x mg-r-5"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel card bd-off">
            <a class="panel-body bg-info bg-darken fg-white">
                <div class="row">
                    <div class="col-xs-7 text-left">
                        <h1 class="mg-0 300">48</h1>
                        NEW TASKS<br>
                        <hr class="hr-white opacity-2 mg-t-5 mg-b-5">
                        <small><i class="fa fa-plus"></i> 14 new this week</small>
                    </div>
                    <div class="col-xs-5 text-right">
                        <i class="fa fa-tasks fa-4x mg-r-5"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>