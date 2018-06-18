<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/OAuth2/OAuth2_Abstract.php');

class Github extends OAuth2_Abstract
{

    public function __construct() {
        parent::__construct();
        $this->_scope = "user:email";
    }

    public function setProvider($data) {
        $this->_provider = new League\OAuth2\Client\Provider\Github([
            'clientId'          => $data->client_id,
            'clientSecret'      => $data->client_secret,
            'redirectUri'       => base_url() .'auth/oauth2/verify/Github'
        ]);
    }
}