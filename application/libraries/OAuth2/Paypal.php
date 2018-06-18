<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/OAuth2/OAuth2_Abstract.php');

class Paypal extends OAuth2_Abstract
{
    public function __construct() {
        parent::__construct();
        $this->_scope = "email";
    }

    public function setProvider($data) {
        $this->_provider = new Stevenmaguire\OAuth2\Client\Provider\Paypal([
            'clientId'          => $data->client_id,
            'clientSecret'      => $data->client_secret,
            'redirectUri'       => base_url() .'auth/oauth2/verify/Paypal',
            'isSandbox'         => false, // Optional, defaults to false. When true, client uses sandbox urls.
        ]);
    }
}