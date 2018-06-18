<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- stylesheet -->
    <link href="<?php echo base_url(); ?>assets/css/<?php echo Settings_model::$db_config['adminpanel_theme']; ?>/bootstrap.min.css" rel="stylesheet">

    <!-- Google web font -->
    <?php $this->load->view('generic/fonts/webfont-source+sans+pro'); ?>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url(); ?><?php echo base_url(); ?>assets/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url(); ?>assets/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url(); ?>assets/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo base_url(); ?>assets/img/favicon/amanifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo base_url(); ?>assets/img/favicon/ams-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
</head>
<body>

    <header>
        <?php echo $template['partials']['header']; ?>
    </header>

    <div class="container mg-t-15">
        <?php echo $template['body']; ?>
    </div>

    <footer class="footer">
        <?php echo $template['partials']['footer']; ?>
    </footer>

    <!-- Bootstrap core JavaScript
        ================================================== -->
    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery-3.2.1.min.js">\x3C/script>')</script>
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/parsley/parsley.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/toastr/toastr.min.js"></script>
    <?php echo $template['js']; ?>
    <?php $this->load->view('generic/js_system'); ?>
    <script src="<?php echo base_url(); ?>assets/js/app.js"></script>

</body>
</html>