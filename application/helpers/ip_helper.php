<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('get_user_ip')) {
    function get_user_ip()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }
}

if (!function_exists('check_ip')) {
    function check_ip($ips) {

        $array = explode(',', str_replace(" ", "", $ips));

        foreach($array as $ip) {
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                return false;
            }
        }

        return true;
    }
}
