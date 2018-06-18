<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// show message when install or migration folder exists
$hook['pre_controller'] = array(
    'class'    => 'Install_folder_exists',
    'function' => 'check_folder_for_existence',
    'filename' => 'Install_folder_exists.php',
    'filepath' => 'hooks',
    'params'   => array('installer') // must be an array
);