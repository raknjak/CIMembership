<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/content_head.php'); ?>

<div class="row">

    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

        <div>
            <?php $this->load->view('generic/flash_error'); ?>
        </div>

        <?php echo form_open('auth/oauth2/finalize','id="oauth2_finalize_form" class="js-parsley mg-b-15" data-parsley-submit="oauth2_finalize_submit"'); ?>

        <?php if (Settings_model::$db_config['oauth_requires_username']) : ?>
        <div class="form-group">
            <label for="oauth2_username"><?php echo $this->lang->line('oauth2_choose_username'); ?></label>
            <input type="text" name="username" id="oauth2_username" class="form-control input-lg"
                   placeholder="Username"
                   data-parsley-pattern="^[a-zA-Z0-9._-]+$"
                   data-parsley-trigger="change keyup"
                   data-parsley-minlength="6"
                   data-parsley-maxlength="24"
                   data-parsley-remote
                   data-parsley-remote-validator="parsley_is_db_cell_available"
                   data-parsley-remote-message="That username is taken."
                   required>
        </div>
        <?php endif; ?>

        <?php sprintf($this->lang->line('oauth2_email_found'), $email); ?>

        <div class="form-group">
            <button type="submit"
                    name="oauth2_finalize_submit"
                    id="oauth2_finalize_submit"
                    class="oauth2_finalize_submit btn btn-primary btn-lg"
                    data-loading-text="<?php echo $this->lang->line('oauth2_finalize_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('oauth2_finish_account_creation'); ?>
            </button>
            <input type="hidden" name="provider" value="<?php echo $this->session->flashdata('provider'); ?>">
            <input type="hidden" name="email" value="<?php echo $this->session->flashdata('email'); ?>">
        </div>

        <?php echo form_close(); ?>

    </div>
</div>