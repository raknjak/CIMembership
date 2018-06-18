<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<?php echo form_open('adminpanel/oauth_new_provider/save', array('id' => 'oauth_new_provider_form','class' => 'js-parsley', 'data-parsley-submit' => 'oauth_new_provider_submit')); ?>

	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<label for="name"><?php echo $this->lang->line('provider_name'); ?></label>
				<input type="text" name="name" id="name" class="form-control" value="<?php echo $this->session->flashdata('name'); ?>"
                required>
			</div>
		</div>
	</div>

    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label for="client_id"><?php echo $this->lang->line('provider_client_id'); ?></label>
                <input type="text" name="client_id" id="client_id" class="form-control" value="<?php echo $this->session->flashdata('client_id'); ?>"
                required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label for="client_secret"><?php echo $this->lang->line('provider_client_secret'); ?></label>
                <input type="text" name="client_secret" id="client_secret" class="form-control" value="<?php echo $this->session->flashdata('client_secret'); ?>"
                required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            <div class="form-group">
                <label for="enabled"><?php echo $this->lang->line('provider_enabled'); ?>?</label>
                <select name="enabled" id="enabled" class="form-control">
                    <option value="1"><?php echo $this->lang->line('yes'); ?></option>
                    <option value="0"><?php echo $this->lang->line('no'); ?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            <div class="form-group">
                <label for="oauth_type"><?php echo $this->lang->line('provider_type'); ?>?</label>
                <select name="oauth_type" id="oauth_type" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
        </div>
    </div>
	
    <div class="form-group">
        <button type="submit" class="oauth_new_provider_submit btn btn-primary btn-lg" data-loading-text="<?php echo $this->lang->line('provider_new_loading_text'); ?>">
            <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('provider_btn_add'); ?>
        </button>
    </div>


<?php echo form_close();