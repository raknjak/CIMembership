<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<?php
if (!Settings_model::$db_config['oauth_enabled']) {
    echo '<p class="f700 bg-danger pd-5 fg-white mg-b-15 text-center"><i class="fa fa-warning fg-warning pd-r-5"></i>'. $this->lang->line('oauth_disabled_warning'). '</p>';
}
?>

    <p>
        <a href="<?php echo base_url(); ?>adminpanel/oauth_new_provider" class="btn btn-default"><?php echo $this->lang->line('provider_add_title'); ?></a>
    </p>

<?php if (!empty($providers)) { ?>

    <p><strong><?php echo $this->lang->line('provider_subtitle'); ?></strong></p>
    <div class="table-responsive">
        <table  class="table table-hover">
            <thead>
            <tr>
                <th style="width: 100px"><?php echo $this->lang->line('provider_order'); ?></th>
                <th><?php echo $this->lang->line('provider_name'); ?></th>
                <th><?php echo $this->lang->line('provider_client_id'); ?></th>
                <th><?php echo $this->lang->line('provider_client_secret'); ?></th>
                <th><?php echo $this->lang->line('provider_enabled'); ?></th>
                <th><?php echo $this->lang->line('oauth_type'); ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($providers as $provider) { ?>

                <?php echo form_open('adminpanel/oauth_providers/action', array('id' => 'save_provider_form', 'autocomplete' => 'off', 'class' => 'js-parsley', 'data-parsley-submit' => 'save_provider')); ?>

                <tr>
                    <td><input style="min-width: 70px;" type="text" name="oauth_order" class="form-control input-lg" value="<?php echo $provider->oauth_order; ?>"></td>
                    <td><input style="min-width: 200px;" type="text" name="name" class="form-control input-lg" value="<?php echo $provider->name; ?>"></td>
                    <td><input style="min-width: 200px;" type="text" name="client_id" class="form-control input-lg" value="<?php echo $this->encryption->decrypt($provider->client_id); ?>"></td>
                    <td><input style="min-width: 200px;" type="text" name="client_secret" class="form-control input-lg" value="<?php echo $this->encryption->decrypt($provider->client_secret); ?>"></td>
                    <td>
                        <?php echo form_dropdown('enabled', $enabled, $provider->enabled, 'class="form-control input-lg" style="min-width: 100px;"'); ?>
                    </td>
                    <td><select class="form-control input-lg" name="oauth_type">
                            <option value="1"<?php echo ($provider->oauth_type == 1 ? 'selected="selected"' : ""); ?>>1</option>
                            <option value="2"<?php echo ($provider->oauth_type == 2 ? 'selected="selected"' : ""); ?>>2</option>
                        </select>
                    </td>
                    <td style="min-width: 250px;">
                        <button type="submit" name="save" class="save_provider btn-lg btn btn-primary"><i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('provider_save'); ?></button>
                        <button type="submit" name="delete" class="btn btn-danger btn-lg js-confirm-delete"><i class="fa fa-trash-o pd-r-5"></i> <?php echo $this->lang->line('provider_delete'); ?></button>
                        <input type="hidden" name="oauth_provider_id" value="<?php echo $provider->oauth_provider_id; ?>">
                    </td>
                </tr>

                <?php echo form_close(); ?>

            <?php } ?>

            </tbody>
        </table>
    </div>
<?php } ?>