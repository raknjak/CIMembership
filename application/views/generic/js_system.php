<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
        var MEMBER = {
          'username': '<?php echo ($this->session->userdata('username') != "" ? $this->session->userdata('username') : ""); ?>',
          'profile_img': '<?php echo ($this->session->userdata('profile_img') != "" ? $this->session->userdata('profile_img') : ""); ?>'
        };

        var LANG = {
            'search_expand' : "<?php echo $this->lang->line('search_expand'); ?>",
            'search_collapse' : "<?php echo $this->lang->line('search_collapse'); ?>",
            'confirm_delete' : "<?php echo $this->lang->line('confirm_delete'); ?>",
            'button_generate_txt' : "<?php echo $this->lang->line('button_generate'); ?>",
            'button_show_txt' : "<?php echo $this->lang->line('button_show'); ?>",
            'button_hide_txt' : "<?php echo $this->lang->line('button_hide'); ?>",
            'button_copy_txt' : "<?php echo $this->lang->line('button_copy'); ?>",
            'copy_to_clipboard_ok' : "<?php echo $this->lang->line('copy_to_clipboard_ok'); ?>",
            'copy_to_clipboard_fail' : "<?php echo $this->lang->line('copy_to_clipboard_fail'); ?>"
        };

        var CONFIG = {
            'base_url': '<?php echo base_url(); ?>',
            'language': '<?php echo $this->config->item('language'); ?>',
            'permitted_uri_chars': '<?php echo $this->config->item('permitted_uri_chars'); ?>',
            'cookie_prefix': '<?php echo $this->config->item('cookie_prefix'); ?>',
            'cookie_domain': '<?php echo $this->config->item('cookie_domain'); ?>',
            'cookie_path': '<?php echo $this->config->item('cookie_path'); ?>',
            'csrf_expire': '<?php echo $this->config->item('csrf_expire'); ?>',
            'csrf_token_name' : "<?php echo $this->security->get_csrf_token_name(); ?>",
            'csrf_cookie_name' : "<?php echo $this->security->get_csrf_hash(); ?>",
            'picture_max_upload_size' : "<?php echo Settings_model::$db_config['picture_max_upload_size']; ?>"
        }
</script>