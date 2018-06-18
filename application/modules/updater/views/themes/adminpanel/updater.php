<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<div>
    <?php $this->load->view('generic/flash_error'); ?>
</div>

<p class="lead alert alert-warning f700"><i class="fa fa-warning pd-r-5"></i> Make a backup of your files and database before proceeding!</p>

<h4 class="mg-b-0">Current version: <?php echo Settings_model::$db_config['cim_version']; ?></h4>
<p>(Updating to version <?php echo $version; ?>)</p>

<p>
    <a class="btn btn-primary" href="<?php echo base_url(); ?>updater/update_now">Update now</a>
</p>