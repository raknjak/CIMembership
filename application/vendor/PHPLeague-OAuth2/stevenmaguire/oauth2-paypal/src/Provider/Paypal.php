<?php namespace Stevenmaguire\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Paypal extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Client is in sandbox mode
     *
     * @var string
     */
    protected $isSandbox = false;

    /**
     * Creates and returns api base url base on client configuration.
     *
     * @return string
     */
    protected function getApiUrl()
    {
        return (bool) $this->isSandbox ? 'https://api.sandbox.paypal.com' : 'https://api.paypal.com';
    }

    /**
     * Creates and returns web app base url base on client configuration.
     *
     * @return string
     */
    protected function getWebAppUrl()
    {
        return (bool) $this->isSandbox ? 'https://www.sandbox.paypal.com' : 'https://www.paypal.com';
    }

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getWebAppUrl().'/webapps/auth/protocol/openidconnect/v1/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getApiUrl().'/v1/identity/openidconnect/tokenservice';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getApiUrl().'/v1/identity/openidconnect/userinfo?schema=openid';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return string[]
     */
    protected function getDefaultScopes()
    {
        return ['openid'];
    }

    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     *
     * @return string Scope separator, defaults to ','
     */
    protected function getScopeSeparator()
    {
        return ' ';
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode > 400) {
            throw new IdentityProviderException(
                $data['message'] ?: $response->getReasonPhrase(),
                $statusCode,
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param object $response
     * @param AccessToken $token
     * @return PaypalResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new PaypalResourceOwner($response);
    }
}
