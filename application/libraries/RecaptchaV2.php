<?php

/**
 * CodeIgniter NO Captcha ReCAPTCHA a.k.a reCAPTCHA Version 2.0 library
 * 
 * This library is based on official reCAPTCHA library for PHP
 * https://github.com/google/ReCAPTCHA
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class ReCaptchaV2 {

    private $signup_url = "https://www.google.com/recaptcha/admin";
    private $_siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?";
    private $_secret, $_sitekey, $_lang;
    private $_version = "php_1.0";

    function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->config('recaptchav2', TRUE);
        if ($this->ci->config->item('recaptcha_secretkey', 'recaptchav2') == NULL || $this->ci->config->item('recaptcha_secretkey', 'recaptchav2') == "") {
            die("To use reCAPTCHA you must get an API key from <a href='"
                    . $this->signup_url . "'>" . $this->signup_url . "</a>");
        }
        if ($this->ci->config->item('recaptcha_sitekey', 'recaptchav2') == NULL || $this->ci->config->item('recaptcha_sitekey', 'recaptchav2') == "") {
            die("To use reCAPTCHA you must get an API key from <a href='"
                    . $this->signup_url . "'>" . $this->signup_url . "</a>");
        }
        $this->_secret = $this->ci->config->item('recaptcha_secretkey', 'recaptchav2');
        $this->_sitekey = $this->ci->config->item('recaptcha_sitekey', 'recaptchav2');
        if ($this->ci->config->item('lang', 'recaptchav2') == NULL || $this->ci->config->item('lang', 'recaptchav2') == "") {
            $this->_lang = 'en';
        } else {
            $this->_lang = $this->ci->config->item('lang', 'recaptchav2');
        }
    }

    /**
     * Function to convert an array into query string
     * @param array $data Array of params
     * @return String query string of parameters
     */
    private function _encodeQS($data) {
        $req = "";
        foreach ($data as $key => $value) {
            $req .= $key . '=' . urlencode(stripslashes($value)) . '&';
        }
        return substr($req, 0, strlen($req) - 1);
    }

    /**
     * HTTP GET to communicate with reCAPTCHA server
     * @param string $path URL to GET
     * @param array $data Array of params
     * @return string JSON response from reCAPTCHA server
     */
    private function _submitHTTPGet($path, $data) {
        $req = $this->_encodeQS($data);
        //$response = file_get_contents($path . $req);

        $handle = curl_init($this->_siteVerifyUrl);
        $options = array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $req,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLINFO_HEADER_OUT => false,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true
        );
        curl_setopt_array($handle, $options);
        $response = curl_exec($handle);
        curl_close($handle);
        return $response;
    }

    /**
     * Function for rendering reCAPTCHA widget into views
     * Call this function in your view
     * @return string embedded HTML
     */
    public function render() {
        $return = '<div class="g-recaptcha" data-sitekey="' . $this->_sitekey . '"></div>';
        return $return;
    }

    /**
     * Function for verifying user's input
     * @param string $response User's input
     * @param string $remoteIp Remote IP you wish to send to reCAPTCHA, if NULL $this->input->ip_address() will be called
     * @return array Array of response
     */
    public function verifyResponse($response, $remoteIp = NULL) {
        if ($response == null || strlen($response) == 0) {
            // Empty user's input
            $return = array(
                'success' => FALSE,
                'error_codes' => 'missing-input'
            );
        }

        $getResponse = $this->_submitHttpGet(
                $this->_siteVerifyUrl, array(
            'secret' => $this->_secret,
            'remoteip' => (!is_null($remoteIp)) ? $remoteIp : $this->ci->input->ip_address(),
            'v' => $this->_version,
            'response' => $response
                )
        );
        $answers = json_decode($getResponse, TRUE);

        if (trim($answers ['success']) == true) {
            // Right captcha! 
            $return = array(
                'success' => TRUE,
                'error_codes' => ''
            );
        } else {
            // Wrong captcha!
            $return = array(
                'success' => FALSE,
                'error_codes' => $answers['error-codes']
            );
        }
        return $return;
    }
}
