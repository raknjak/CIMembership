<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<ul id="demo1" class="menu">

    <?php if (Site_Controller::check_roles(array(1,2,3))) {  ?>
    <li class="hidden-folded">
        <div class="menu-section-title text-left"><?php echo $this->lang->line('menu_adminpanel'); ?></div>
    </li>
    <li<?php echo ((Site_Controller::$page == "dashboard") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>adminpanel/dashboard"><span class="menu-link-icon fa fa-dashboard"></span> <span class="menu-link-title"><?php echo $this->lang->line('menu_dashboard'); ?></span></a></li>

    <li><a href="javascript:"><span class="menu-link-icon fa fa-cogs"></span> <span class="menu-link-title"><?php echo $this->lang->line('menu_settings'); ?></span></a>
        <ul>
            <li<?php echo ((Site_Controller::$page == "site_settings") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>adminpanel/site_settings"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_cim_settings'); ?></a>
            <li<?php echo ((Site_Controller::$page == "manage_ci_config") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>adminpanel/manage_ci_config"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_ci_config'); ?></a>
        </ul>
    </li>

    <li><a href="javascript:"><span class="menu-link-icon fa fa-users"></span> <span class="menu-link-title"><?php echo $this->lang->line('menu_membership'); ?></span></a>
        <ul>
            <li<?php echo ((Site_Controller::$page == "list_members") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>adminpanel/list_members"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_list_members'); ?></a>
            <li<?php echo ((Site_Controller::$page == "add_member") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>adminpanel/add_member"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_add_member'); ?></a>
        </ul>
    </li>

    <li><a href="javascript:"><span class="menu-link-icon fa fa-diamond"></span> <span class="menu-link-title"><?php echo $this->lang->line('menu_roles_permissions'); ?></span></a>
        <ul>
            <li<?php echo ((Site_Controller::$page == "roles") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>adminpanel/roles"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_roles'); ?></a>
            <li<?php echo ((Site_Controller::$page == "permissions") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>adminpanel/permissions"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_permissions'); ?></a>
        </ul>
    </li>

    <li<?php echo ((Site_Controller::$page == "oauth_providers") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>adminpanel/oauth_providers"><span class="menu-link-icon fa fa-plug"></span> <span class="menu-link-title"><?php echo $this->lang->line('menu_oauth_providers'); ?></span></a>
    <li<?php echo ((Site_Controller::$page == "backup_export") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>adminpanel/backup_export"><span class="menu-link-icon fa fa-database"></span> <span class="menu-link-title"><?php echo $this->lang->line('menu_backup_export'); ?></span></a>
    <?php } ?>

    <li class="hidden-folded">
        <div class="menu-section-title text-left"><?php echo $this->lang->line('menu_member'); ?></div>
    </li>

    <li<?php echo ((Site_Controller::$page == "my_dashboard") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>membership/my_dashboard"><span class="menu-link-icon fa fa-th-list"></span> <span class="menu-link-title"><?php echo $this->lang->line('menu_my_dashboard'); ?></span></a></li>
    <li<?php echo ((Site_Controller::$page == "profile") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>membership/profile"><span class="menu-link-icon fa fa-user"></span> <span class="menu-link-title"><?php echo $this->lang->line('menu_profile'); ?></span></a></li>

    <li class="hidden-folded">
        <div class="menu-section-title text-left"><?php echo $this->lang->line('menu_layout_options'); ?></div>
    </li>

    <li><a href="javascript:"><span class="menu-link-icon fa fa-list-alt"></span> <span class="menu-link-title"><?php echo $this->lang->line('menu_page_layouts'); ?></span></a>
        <ul>
            <li<?php echo ((Site_Controller::$page == "left_menu_fluid") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>page_layouts/left_menu_fluid"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_left_menu_fluid'); ?></a></li>
            <li<?php echo ((Site_Controller::$page == "left_menu_fixed") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>page_layouts/left_menu_fixed"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_left_menu_fixed'); ?></a></li>
            <li<?php echo ((Site_Controller::$page == "right_menu_fluid") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>page_layouts/right_menu_fluid"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_right_menu_fluid'); ?></a></li>
            <li<?php echo ((Site_Controller::$page == "right_menu_fixed") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>page_layouts/right_menu_fixed"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_right_menu_fixed'); ?></a></li>
            <li<?php echo ((Site_Controller::$page == "header_fluid") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>page_layouts/header_fluid"><span class="menu-link-icon fa fa-angle-right"></span> <?php echo $this->lang->line('menu_header_fluid'); ?></a></li>
            <li<?php echo ((Site_Controller::$page == "fixed_header_fluid") ? ' class="open"' : ""); ?>><a href="<?php echo base_url(); ?>page_layouts/fixed_header_fluid"><span class="menu-link-icon fa fa-angle-right"></span><?php echo $this->lang->line('menu_fixed_header_fluid'); ?></a></li>
        </ul>
    </li>
</ul>