<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<aside class="aside">
    <div>
        <?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/aside-inner.php'); ?>
        <?php $this->load->view('themes/adminpanel/partials/aside-below.php'); ?>
    </div>
</aside>