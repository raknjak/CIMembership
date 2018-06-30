Twitch provider for league/oauth2-client
=========================================

This is a package to integrate twitch.tv authentication with the [OAuth2 client library](https://github.com/thephpleague/oauth2-client) by
[The League of Extraordinary Packages](http://thephpleague.com).

To install, use composer:

```bash
composer require depotwarehouse/oauth2-twitch
```

Usage is the same as the league's OAuth client, using `\Depotwarehouse\OAuth2\Client\Twitch\Provider\Twitch` as the provider.
For example:

```php
$provider = new \Depotwarehouse\OAuth2\Client\Twitch\Provider\Twitch([
    'clientId' => "YOUR_CLIENT_ID",
    'clientSecret' => "YOUR_CLIENT_SECRET",
    'redirectUri' => "http://your-redirect-uri"
]);
```

You can also optionally add a `scopes` key to the array passed to the constructor. The available scopes are documented
on the [Twitch API Documentation](https://github.com/justintv/Twitch-API/blob/master/authentication.md).

> Note: The provider uses the "user_read" scope by default. If you pass other scopes, and want the ->getResourceOwner() method
to work, you will need to ensure the "user_read" scope is used.

```php
if (isset($_GET['code']) && $_GET['code']) {
    $token = $this->provider->getAccessToken("authorization_code", [
        'code' => $_GET['code']
    ]);

    // Returns an instance of Depotwarehouse\OAuth2\Client\Twitch\Entity\TwitchUser
    $user = $this->provider->getResourceOwner($token);
    
    $user->getDisplayName();
    $user->getId()
    $user->getType();
    $user->getBio();
    $user->getEmail();
    $user->getPartnered();
```

Laravel Framework Integration
------------------------------

This package includes Laravel framework integration if you need it. Simply require it as normal in your Laravel application,
and add the Service Provider `Depotwarehouse\OAuth2\Client\Twitch\FrameworkIntegration\Laravel\TwitchOAuth2ServiceProvider` to your `config/app.php`.

Next, publish the configuration with `php artisan vendor:publish`, and fill out your client
details in the `config/depotwarehouse/oauth2-twitch/config.php` file that is generated.

This will register bindings in the IoC container for the Twitch Provider, so you can simply typehint the
`\Depotwarehouse\OAuth2\Client\Twitch\Provider\Twitch` in your controller methods and it will yield a properly configured
instance.

Testing
---------
You can quickly test that the package works by adding client information (from your twitch.tv account) to `config/config.php`
and then starting up a php server

```bash
cd test/
php -S localhost:8000
```

Now, navigating to `http://localhost:8000` should present an OAuth flow and then dump your user information.
