<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/content_head.php'); ?>

<div class="row">

    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

        <div>
            <?php if (!Settings_model::$db_config['register_enabled']) { ?>

                <div id="error" class="alert alert-danger">
                    <?php echo $this->lang->line('registration_disabled'); ?>
                </div>

            <?php
                }else{
                    $this->load->view('generic/flash_error');
                }
            ?>
        </div>

        <div>
            <?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/auth_oauth.php'); ?>
        </div>

        <div id="regular_wrapper">
            <?php echo form_open('auth/register/add_member','id="register_form" class="js-parsley mg-b-15" data-parsley-submit="register_submit"'); ?>

            <p class="f700">
                <?php echo $this->lang->line('register_required_fields'); ?>
            </p>

            <div class="form-group">
                <input type="text" name="first_name" id="first_name" class="form-control input-lg"
                       placeholder="<?php echo $this->lang->line('register_first_name'); ?>"
                       value="<?php echo $this->session->flashdata('first_name'); ?>"
                       data-parsley-trigger="change keyup"
                       data-parsley-minlength="2"
                       data-parsley-maxlength="40"
                       required>
                <p class="small pd-5">
                    <strong><?php echo $this->lang->line('register_req_first_name'); ?></strong>
                </p>
            </div>

            <div class="form-group">
                <input type="text" name="last_name" id="last_name" class="form-control input-lg"
                       placeholder="<?php echo $this->lang->line('register_last_name'); ?>"
                       value="<?php echo $this->session->flashdata('last_name'); ?>"
                       data-parsley-trigger="change keyup"
                       data-parsley-minlength="2"
                       data-parsley-maxlength="60"
                       required>
                <p class="small pd-5">
                    <strong><?php echo $this->lang->line('register_req_last_name'); ?></strong>
                </p>
            </div>

            <div class="form-group">
                <input type="email" name="email" id="email" class="form-control input-lg"
                       placeholder="<?php echo $this->lang->line('register_email_address'); ?>"
                       value="<?php echo $this->session->flashdata('email'); ?>"
                       data-parsley-type="email"
                       data-parsley-trigger="change keyup"
                       data-parsley-maxlength="254"
                       data-parsley-remote
                       data-parsley-remote-validator="parsley_is_db_cell_available"
                       data-parsley-remote-message="<?php echo $this->lang->line('form_validation_is_db_cell_available'); ?>"
                       required>
                <p class="small pd-5">
                    <strong><?php echo $this->lang->line('register_req_email'); ?></strong>
                </p>
            </div>

            <?php if (Settings_model::$db_config['registration_requires_username']) : ?>
            <div class="form-group">
                <input type="text" name="username" id="username" class="form-control input-lg"
                       placeholder="<?php echo $this->lang->line('register_username'); ?>"
                       value="<?php echo $this->session->flashdata('username'); ?>"
                       data-parsley-pattern="^[a-zA-Z0-9._-]+$"
                       data-parsley-trigger="change keyup"
                       data-parsley-minlength="6"
                       data-parsley-maxlength="24"
                       data-parsley-remote
                       data-parsley-remote-validator="parsley_is_db_cell_available"
                       data-parsley-remote-message="That username is taken."
                       required>
                <p class="small pd-5">
                    <strong><?php echo $this->lang->line('register_req_username'); ?></strong>
                </p>
            </div>
            <?php endif; ?>

            <?php if (Settings_model::$db_config['registration_requires_password']) : ?>
            <div class="form-group pd-10 bg-info fg-white text-center f700">
                <?php echo $this->lang->line('register_password_warning'); ?>
            </div>

            <div class="form-group">
                <div class="btn-group js-password-btn-group" role="group" aria-label="...">
                    <a href="javascript:" class="btn btn-default js-genWordsButton"><?php echo $this->lang->line('button_generate'); ?></a>
                    <a href="javascript:" class="btn btn-default js-show-pwd"><?php echo $this->lang->line('button_show'); ?></a>
                </div>
            </div>

            <div class="form-group">
                <input type="password" name="password" id="password" class="form-control input-lg"
                       placeholder="<?php echo $this->lang->line('register_password'); ?>"
                       value=""
                       data-parsley-pattern="^(?=.{9,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\.\@\#\$\%\^\|\?\*\!\:\-\;\&\+\=\{\}\[\]]).*$"
                       data-parsley-trigger="change keyup"
                       data-parsley-maxlength="255"
                       data-parsley-pattern-message="<?php echo $this->lang->line('register_req_password_parsley'); ?>"
                       required>

                <p class="small pd-5">
                    <strong><?php echo $this->lang->line('register_req_password'); ?></strong>
                </p>
            </div>

            <div class="form-group">
                <input type="password" name="password_confirm" id="password_confirm" class="form-control input-lg"
                       placeholder="<?php echo $this->lang->line('register_password_confirm'); ?>"
                       value=""
                       data-parsley-equalto="#password"
                       required>
                <p class="small pd-5">
                    <strong><?php echo $this->lang->line('register_req_password_2'); ?></strong>
                </p>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <?php
                if (Settings_model::$db_config['recaptchav2_enabled'] == true) {
                    echo $this->recaptchav2->render();
                }
                ?>
            </div>

            <div class="form-group">
                <button type="submit" name="register_submit" id="register_submit" class="register_submit btn btn-primary btn-lg" data-loading-text="<?php echo $this->lang->line('register_btn_loading_text'); ?>">
                    <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('register_button_text'); ?>
                </button>
            </div>

            <?php echo form_close(); ?>
        </div>

        <?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/auth_links'); ?>

    </div>

</div>


