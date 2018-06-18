<?php

require '../vendor/autoload.php';
require 'config.php';

$provider = new \Depotwarehouse\OAuth2\Client\Twitch\Provider\Twitch(
    $config
);

if (isset($_GET['code']) && $_GET['code']) {
    $token = $provider->getAccessToken("authorization_code", [
        'code' => $_GET['code']
    ]);

    $user = $provider->getResourceOwner($token);
    var_dump($user);


} else {
    header('Location: ' . $provider->getAuthorizationUrl());
}
