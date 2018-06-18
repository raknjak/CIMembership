<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (Settings_model::$db_config['oauth_enabled']) { ?>
    <div class="mg-b-15">
        <?php
        $i=0;
        if ($providers) {?>
            <div id="social_login_wrapper">
        <ul class="list-unstyled list-inline">
            <?php
            //$count = 1;
            foreach ($providers as $provider) {
                //($count%5 == 0 ? print "<br>" : "");
                ?>
            <li><a href="<?php print base_url(); ?>auth/oauth<?php print $provider->oauth_type; ?>/init/<?php print $provider->name; ?>">
                <img src="<?php print base_url() . "assets/img/social_icons/". strtolower($provider->name) .".png"; ?>">
            </a></li>
        <?php /*$count++;*/ } ?></ul></div><?php } ?>
    </div>

<?php } ?>