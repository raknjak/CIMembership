<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/OAuth2/OAuth2_Abstract.php');

class Trello
{
    public function setProvider($data) {
        $server =  new League\OAuth1\Client\Server\Trello(array(
            'identifier' => 'your-identifier',
            'secret' => 'your-secret',
            'callback_uri' => 'http://your-callback-uri/',
            'name' => 'your-application-name', // optional, defaults to null
            'expiration' => 'your-application-expiration', // optional ('never', '1day', '2days'), defaults to '1day'
            'scope' => 'your-application-scope' // optional ('read', 'read,write'), defaults to 'read'
        ));
        return new League\OAuth1\Client\Server\Trello(array(
            'identifier'        => $data->client_id,
            'secret'            => $data->client_secret,
            'callback_uri'      => base_url() .'auth/oauth1/verify/Trello',
            'scope' => 'read'
        ));
    }
}