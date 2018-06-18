<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<?php echo form_open('adminpanel/list_members/index/username/asc/post/0', array('id' => 'member_search', 'class' => 'js-parsley', 'data-parsley-submit' => 'member_search_submit')) ."\r\n"; ?>

<button id="js-search" type="button" class="btn btn-default" data-toggle="collapse" data-target="#search_wrapper">
    <span id="js-search-text"><i class="fa fa-expand pd-r-5"></i> <?php echo $this->lang->line('list_members_search_expand'); ?></span> <?php echo $this->lang->line('list_members_search'); ?> <i class="fa fa-search pd-l-5"></i>
</button>

    <div id="search_wrapper" class="collapse">

        <div class="pd-15 bg-primary mg-t-15 mg-b-10">
            <h2 class="text-uppercase mg-t-0 fg-white">
                <?php echo $this->lang->line('list_members_search_member'); ?>
            </h2>

            <div class="row">

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="username"><?php echo $this->lang->line('list_members_username'); ?></label>
                        <input type="text" name="username" id="username" class="form-control"
                               data-parsley-trigger="change keyup">
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="first_name"><?php echo $this->lang->line('list_members_first_name'); ?></label>
                        <input type="text" name="first_name" id="first_name" class="form-control"
                               data-parsley-trigger="change keyup">
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="last_name"><?php echo $this->lang->line('list_members_last_name'); ?></label>
                        <input type="text" name="last_name" id="last_name" class="form-control"
                               data-parsley-trigger="change keyup">
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="email"><?php echo $this->lang->line('list_members_email_address'); ?></label>
                        <input type="text" name="email" id="email" class="form-control"
                               data-parsley-trigger="change keyup">
                    </div>
                </div>
            </div>
        </div>
		
		<div class="row mg-b-20">
			<div class="col-xs-12 clearfix">
                <button type="submit" name="member_search_submit" id="member_search_submit" class="member_search_submit btn btn-primary btn-lg" data-loading-text="<?php echo $this->lang->line('list_members_search_loading_text'); ?>">
                    <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('list_members_search_member'); ?>
                </button>
            </div>
		</div>
    </div>
<?php echo form_close() ."\r\n"; ?>

	<div class="row margin-top-30">
		<div class="col-xs-12">
		
			<h4 class="f700">
				<?php echo  $this->lang->line('list_members_total') .": ". $total_rows; ?>
			</h4>

			<?php if (isset($members)) { ?>

			<?php echo form_open('adminpanel/list_members/mass_action/'. $offset .'/'. $order_by .'/'. $sort_order .'/'. $search, 'id="mass_action_form"') ."\r\n"; ?>

            <?php $this->load->view('list_members_action.php'); ?>

            <div class="table-responsive">
                <table class="table table-hover table-list-members">
                    <thead>
                    <tr>
                        <th style="width: 1px;">
                            <div class="app-checkbox">
                                <label class="pd-r-10">
                                    <input type="checkbox" class="js-select-all-members">
                                    <span class="fa fa-check"></span>
                                </label>
                            </div>
                        </th>
                        <th class="text-center"><a href="<?php echo base_url() ."adminpanel/list_members/index/active/". ($order_by == "active" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>" class="<?php echo ($order_by == "active" ? ($sort_order == "asc" ? "fa fa-arrow-circle-o-up" : "fa fa-arrow-circle-o-down" ) : ""); ?>"><i class="fa fa-plug"></i></a></th>
                        <?php if (Settings_model::$db_config['registration_approval_required']) : ?>
                        <th class="text-center"><a href="<?php echo base_url() ."adminpanel/list_members/index/approved/". ($order_by == "approved" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>" class="<?php echo ($order_by == "approved" ? ($sort_order == "asc" ? "fa fa-arrow-circle-o-up" : "fa fa-arrow-circle-o-down" ) : ""); ?>"><i class="fa fa-check-circle"></i></a></th>
                        <?php endif; ?>
                        <th class="text-center"><a href="<?php echo base_url() ."adminpanel/list_members/index/banned/". ($order_by == "banned" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>" class="<?php echo ($order_by == "banned" ? ($sort_order == "asc" ? "fa fa-arrow-circle-o-up" : "fa fa-arrow-circle-o-down" ) : ""); ?>"><i class="fa fa-gavel"></i></a></th>
                        <th><a href="<?php echo base_url() ."adminpanel/list_members/index/username/". ($order_by == "username" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>"><i class="<?php echo ($order_by == "username" ? ($sort_order == "asc" ? "fa fa-arrow-circle-o-up" : "fa fa-arrow-circle-o-down" ) : ""); ?>"></i> <?php echo $this->lang->line('list_members_username'); ?></a></th>
                        <th><a href="<?php echo base_url() ."adminpanel/list_members/index/email/". ($order_by == "email" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>"><i class="<?php echo ($order_by == "email" ? ($sort_order == "asc" ? "fa fa-arrow-circle-o-up" : "fa fa-arrow-circle-o-down" ) : ""); ?>"></i> <?php echo $this->lang->line('list_members_email'); ?></a></th>
                        <th><a href="<?php echo base_url() ."adminpanel/list_members/index/first_name/". ($order_by == "first_name" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>"><i class="<?php echo ($order_by == "first_name" ? ($sort_order == "asc" ? "fa fa-arrow-circle-o-up" : "fa fa-arrow-circle-o-down" ) : ""); ?>"></i> <?php echo $this->lang->line('list_members_first_name'); ?></a></th>
                        <th><a href="<?php echo base_url() ."adminpanel/list_members/index/last_name/". ($order_by == "last_name" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>"><i class="<?php echo ($order_by == "last_name" ? ($sort_order == "asc" ? "fa fa-arrow-circle-o-up" : "fa fa-arrow-circle-o-down" ) : ""); ?>"></i> <?php echo $this->lang->line('list_members_last_name'); ?></a></th>
                        <th><a href="<?php echo base_url() ."adminpanel/list_members/index/date_registered/". ($order_by == "date_registered" ? ($sort_order == "asc" ? "desc" : "asc" ) : "desc") ."/". $search ."/0"; ?>"><i class="<?php echo ($order_by == "date_registered" ? ($sort_order == "asc" ? "fa fa-arrow-circle-o-up" : "fa fa-arrow-circle-o-down" ) : ""); ?>"></i> <?php echo $this->lang->line('date_registered'); ?></a></th>
                        <th><a href="<?php echo base_url() ."adminpanel/list_members/index/last_login/". ($order_by == "last_login" ? ($sort_order == "asc" ? "desc" : "asc" ) : "desc") ."/". $search ."/0"; ?>"><i class="<?php echo ($order_by == "last_login" ? ($sort_order == "asc" ? "fa fa-arrow-circle-o-up" : "fa fa-arrow-circle-o-down" ) : ""); ?>"></i> <?php echo $this->lang->line('last_login'); ?></a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($members->result() as $member):
                    ?>
                    <tr<?php if($member->banned == true) {echo ' style="text-decoration: line-through;"';} ?>>
                        <td>
                            <?php if ($member->username != Settings_model::$db_config['root_admin_username']) { ?>
                            <div class="app-checkbox">
                                <label class="pd-r-10">
                                    <input type="checkbox" name="mass[]" value="<?php echo $member->user_id; ?>" class="list_members_checkbox">
                                    <span class="fa fa-check"></span>
                                </label>
                            </div>
                        <?php } ?>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo site_url('adminpanel/list_members/toggle_active/'. $member->user_id ."/". $member->username ."/". $offset .'/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $member->active); ?>" title="<?php echo ($member->active == true ? $this->lang->line('list_members_deactivate') : $this->lang->line('list_members_activate')); ?>">
                                <i class="listicon fa <?php echo ($member->active == true ? "fa-check fg-success" : "fa-times fg-danger"); ?>"></i>
                            </a>
                        </td>
                        <?php if (Settings_model::$db_config['registration_approval_required']) : ?>
                        <td class="text-center">
                            <a href="<?php echo site_url('adminpanel/list_members/toggle_approval/'. $member->user_id ."/". $member->username ."/". $offset .'/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $member->approved); ?>" title="<?php echo ($member->approved == true ? $this->lang->line('list_members_approve') : $this->lang->line('list_members_unapprove')); ?>">
                                <i class="listicon fa <?php echo ($member->approved == true ? "fa-thumbs-o-down fg-danger" : "fa-thumbs-o-up fg-success"); ?>"></i>
                            </a>
                        </td>
                        <?php endif; ?>
                        <td class="text-center">
                            <a href="<?php echo site_url('adminpanel/list_members/toggle_ban/'. $member->user_id ."/". $member->username ."/". $offset .'/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $member->banned); ?>" title="<?php echo ($member->banned == true ? $this->lang->line('list_members_unban') : $this->lang->line('list_members_ban')); ?>">
                                <i class="listicon fa <?php echo ($member->banned == true ? "fa-lock fg-danger" : "fa-unlock fg-success"); ?>"></i>
                            </a>
                        </td>

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
                        <td>
                            <span class="pd-r-10">
                                <a href="<?php echo base_url(); ?>adminpanel/contact_member/<?php echo $member->user_id; ?>"><i class="fa fa-envelope-o"></i><sup><i class="fa fa-user"></i></sup></a>
                            </span>
                            <?php echo $member->email; ?>
                        </td>


                        <td><?php echo (!empty($member->first_name) ? $member->first_name : "(n/a)"); ?></td>
                        <td><?php echo (!empty($member->last_name) ? $member->last_name : "(n/a)"); ?></td>
                        <td><?php echo date('M j, Y', strtotime($member->date_registered)); ?> <small>(<?php echo date('H:i', strtotime($member->date_registered)); ?>h)</small></td>
                        <td><?php echo date('M j, Y', strtotime($member->last_login)); ?> <small>(<?php echo date('H:i', strtotime($member->last_login)); ?>h)</small></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php $this->load->view('list_members_action.php'); ?>

			<input type="hidden" name="mass_action" id="mass_action" value="">

			<?php echo form_close() ."\r\n"; ?>

			<?php echo $this->pagination->create_links(); ?>

			<?php }else{ ?>
				<p><?php echo $this->lang->line('list_members_no_results'); ?></p>
			<?php } ?>

		</div>
	</div>


