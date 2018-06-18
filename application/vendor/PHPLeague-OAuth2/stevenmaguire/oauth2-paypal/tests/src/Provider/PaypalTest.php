<?php namespace Stevenmaguire\OAuth2\Client\Test\Provider;

use Mockery as m;

class PaypalTest extends \PHPUnit_Framework_TestCase
{
    protected $provider;

    protected function setUp()
    {
        $this->provider = new \Stevenmaguire\OAuth2\Client\Provider\Paypal([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertNotNull($this->provider->getState());
    }


    public function testScopes()
    {
        $options = ['scope' => [uniqid(),uniqid()]];

        $url = $this->provider->getAuthorizationUrl($options);

        $this->assertContains(urlencode(implode(' ', $options['scope'])), $url);
    }

    public function testDefaultScopes()
    {
        $url = $this->provider->getAuthorizationUrl();

        $this->assertContains('openid', $url);
    }

    public function testSandbox()
    {
        $provider = new \Stevenmaguire\OAuth2\Client\Provider\Paypal([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
            'isSandbox' => true,
        ]);

        $authUrl = $provider->getAuthorizationUrl();
        $tokenUrl = $provider->getBaseAccessTokenUrl([]);

        $this->assertContains('https://www.sandbox.paypal.com', $authUrl);
        $this->assertContains('https://api.sandbox.paypal.com', $tokenUrl);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('www.paypal.com', $uri['host']);
        $this->assertEquals('/webapps/auth/protocol/openidconnect/v1/authorize', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('api.paypal.com', $uri['host']);
        $this->assertEquals('/v1/identity/openidconnect/tokenservice', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn('{"access_token": "mock_access_token", "token_type":"bearer", "expires_in":3600, "refresh_token":"mock_refresh_token"}');
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertLessThanOrEqual(time() + 3600, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertNull($token->getResourceOwnerId());
    }

    public function testUserData()
    {
        $userId = rand(1000,9999);
        $name = uniqid();
        $given_name = uniqid();
        $family_name = uniqid();
        $email = uniqid();
        $verified = (bool) rand(0,1);
        $gender = uniqid();
        $birthdate = uniqid();
        $zoneinfo = uniqid();
        $locale = uniqid();
        $phone_number = uniqid();
        $verified_account = (bool) rand(0,1);
        $account_type = uniqid();
        $age_range = uniqid();
        $payer_id = uniqid();


        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn('{"access_token": "mock_access_token", "token_type":"bearer", "expires_in":3600, "refresh_token":"mock_refresh_token"}');
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $postResponse->shouldReceive('getStatusCode')->andReturn(200);

        $userResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $userResponse->shouldReceive('getBody')->andReturn('{"user_id": "'.$userId.'","name": "'.$name.'","given_name": "'.$given_name.'","family_name": "'.$family_name.'","email": "'.$email.'","verified": '.($verified ? 'true' : 'false').',"gender": "'.$gender.'","birthdate": "'.$birthdate.'","zoneinfo": "'.$zoneinfo.'","locale": "'.$locale.'","phone_number": "'.$phone_number.'","address": { },"verified_account": '.($verified_account ? 'true' : 'false').',"account_type": "'.$account_type.'","age_range": "'.$age_range.'","payer_id": "'.$payer_id.'"}');
        $userResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $userResponse->shouldReceive('getStatusCode')->andReturn(200);

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(2)
            ->andReturn($postResponse, $userResponse);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $user = $this->provider->getResourceOwner($token);

        $this->assertEquals($userId, $user->getId());
        $this->assertEquals($userId, $user->toArray()['user_id']);
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($name, $user->toArray()['name']);
        $this->assertEquals($given_name, $user->getGivenName());
        $this->assertEquals($given_name, $user->toArray()['given_name']);
        $this->assertEquals($family_name, $user->getFamilyName());
        $this->assertEquals($family_name, $user->toArray()['family_name']);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($email, $user->toArray()['email']);

        if ($verified) {
            $this->assertTrue($user->isVerified());
            $this->assertTrue($user->toArray()['verified']);
        } else {
            $this->assertFalse($user->isVerified());
            $this->assertFalse($user->toArray()['verified']);
        }

        $this->assertEquals($gender, $user->getGender());
        $this->assertEquals($gender, $user->toArray()['gender']);
        $this->assertEquals($birthdate, $user->getBirthdate());
        $this->assertEquals($birthdate, $user->toArray()['birthdate']);
        $this->assertEquals($zoneinfo, $user->getZoneinfo());
        $this->assertEquals($zoneinfo, $user->toArray()['zoneinfo']);
        $this->assertEquals($locale, $user->getLocale());
        $this->assertEquals($locale, $user->toArray()['locale']);
        $this->assertEquals($phone_number, $user->getPhoneNumber());
        $this->assertEquals($phone_number, $user->toArray()['phone_number']);
        $this->assertTrue(is_array($user->getAddress()));

        if ($verified_account) {
            $this->assertTrue($user->isVerifiedAccount());
            $this->assertTrue($user->toArray()['verified_account']);
        } else {
            $this->assertFalse($user->isVerifiedAccount());
            $this->assertFalse($user->toArray()['verified_account']);
        }

        $this->assertEquals($account_type, $user->getAccountType());
        $this->assertEquals($account_type, $user->toArray()['account_type']);
        $this->assertEquals($age_range, $user->getAgeRange());
        $this->assertEquals($age_range, $user->toArray()['age_range']);
        $this->assertEquals($payer_id, $user->getPayerId());
        $this->assertEquals($payer_id, $user->toArray()['payer_id']);
    }

    /**
     * @expectedException League\OAuth2\Client\Provider\Exception\IdentityProviderException
     **/
    public function testExceptionThrownWhenErrorObjectReceived()
    {
        $status = rand(401,599);
        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn('{"name": "mock_error_name","message": "mock_error_message","information_link": "mock_error_link","details": "mock_error_details"}');
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $postResponse->shouldReceive('getStatusCode')->andReturn($status);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(1)
            ->andReturn($postResponse);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }
}
