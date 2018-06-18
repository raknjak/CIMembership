# Paypal Provider for OAuth 2.0 Client

[![Latest Version](https://img.shields.io/github/release/stevenmaguire/oauth2-paypal.svg?style=flat-square)](https://github.com/stevenmaguire/oauth2-paypal/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/stevenmaguire/oauth2-paypal/master.svg?style=flat-square)](https://travis-ci.org/stevenmaguire/oauth2-paypal)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/stevenmaguire/oauth2-paypal.svg?style=flat-square)](https://scrutinizer-ci.com/g/stevenmaguire/oauth2-paypal/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/stevenmaguire/oauth2-paypal.svg?style=flat-square)](https://scrutinizer-ci.com/g/stevenmaguire/oauth2-paypal)
[![Total Downloads](https://img.shields.io/packagist/dt/stevenmaguire/oauth2-paypal.svg?style=flat-square)](https://packagist.org/packages/stevenmaguire/oauth2-paypal)

This package provides Paypal OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require stevenmaguire/oauth2-paypal
```

## Usage

Usage is the same as The League's OAuth client, using `\Stevenmaguire\OAuth2\Client\Provider\Paypal` as the provider.

### Authorization Code Flow

```php
$provider = new Stevenmaguire\OAuth2\Client\Provider\Paypal([
    'clientId'          => '{paypal-client-id}',
    'clientSecret'      => '{paypal-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
    'isSandbox'         => true, // Optional, defaults to false. When true, client uses sandbox urls.
]);
```

For further usage of this package please refer to the [core package documentation on "Authorization Code Grant"](https://github.com/thephpleague/oauth2-client#usage).

#### Managing scopes with your authorization request

```php
$options = [
    'scope' => ['openid', 'profile', 'email', 'phone', 'address']
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```

>The value passed must always include `openid` at minimum

You can review a [full list of scopes](https://developer.paypal.com/docs/integration/direct/identity/attributes/) on PayPal's website.

### Refreshing a Token

```php
$provider = new Stevenmaguire\OAuth2\Client\Provider\Paypal([
    'clientId'          => '{paypal-client-id}',
    'clientSecret'      => '{paypal-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url'
]);

$existingAccessToken = getAccessTokenFromYourDataStore();

if ($existingAccessToken->hasExpired()) {
    $newAccessToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $existingAccessToken->getRefreshToken()
    ]);

    // Purge old access token and store new access token to your data store.
}
```

For further usage of this package please refer to the [core package documentation on "Refreshing a Token"](https://github.com/thephpleague/oauth2-client#refreshing-a-token).



## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/stevenmaguire/oauth2-paypal/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Steven Maguire](https://github.com/stevenmaguire)
- [All Contributors](https://github.com/stevenmaguire/oauth2-paypal/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/stevenmaguire/oauth2-paypal/blob/master/LICENSE) for more information.
