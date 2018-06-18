<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/content_head.php'); ?>

<div class="row">

    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

        <?php
            $this->load->view('generic/flash_error');
        ?>

        <div>
        <?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/auth_oauth.php'); ?>
        </div>

        <?php echo form_open('auth/login/validate', 'id="login_form" class="js-parsley mg-b-15" data-parsley-submit="login_submit"'); ?>

        <div class="form-group">
            <input type="text"
                name="identification"
                id="identification"
                class="form-control input-lg"
                placeholder="<?php echo sprintf((Settings_model::$db_config['allow_login_by_email'] == true ? $this->lang->line('login_identification') : $this->lang->line('login_username')), (Settings_model::$db_config['allow_login_by_email'] == true ? " or email" : "")); ?>"
                data-parsley-trigger="change keyup"
                data-parsley-minlength="6"
                data-parsley-maxlength="255"
                required>
        </div>

        <div class="form-group">
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control input-lg"
                   placeholder="<?php echo $this->lang->line('login_password'); ?>"
                   required>
        </div>

        <div class="form-group">
        <?php if (Settings_model::$db_config['remember_me_enabled'] == true) { ?>
            <div class="app-checkbox">
                <label class="pd-r-10">
                    <?php echo form_checkbox(array('name' => 'remember_me', 'id' =>'remember_me', 'value' => 'accept', 'checked' => FALSE)); ?>
                    <span class="fa fa-check"></span> <?php echo $this->lang->line('login_remember_me'); ?>
                </label>
            </div>
        <?php } ?>
        </div>

        <?php
        if ($this->session->userdata('login_attempts') == false) {
            $v = 0;
        }else{
            $v = $this->session->userdata('login_attempts');
        }

        if ($v >= Settings_model::$db_config['login_attempts']) {
            if (Settings_model::$db_config['recaptchav2_enabled'] == true) {
                //echo $this->recaptcha->get_html();
                ?><div class="mg-b-15 mg-t-15"><?php
                echo $this->recaptchav2->render();
                ?></div><?php
            }
        }
        ?>

        <div class="form-group">
            <button type="submit" name="login_submit" id="login_submit" class="login_submit btn btn-primary btn-lg" data-loading-text="<?php echo $this->lang->line('login_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php echo $this->lang->line('login'); ?>
            </button>
        </div>

        <?php if (Settings_model::$db_config['previous_url_after_login']) { ?>
        <input type="hidden" name="previous_url" value="<?php echo base64_encode($this->session->flashdata('previous_url')); ?>">
        <?php } ?>

        <?php echo form_close(); ?>

        <?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/auth_links'); ?>

    </div>

</div>