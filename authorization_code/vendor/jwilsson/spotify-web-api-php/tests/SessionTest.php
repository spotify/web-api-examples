<?php

declare(strict_types=1);

class SessionTest extends PHPUnit\Framework\TestCase
{
    private $clientID = 'b777292af0def22f9257991fc770b520';
    private $clientSecret = '6a0419f43d0aa93b2ae881429b6b9bc2';
    private $redirectURI = 'https://example.com/callback';
    private $accessToken = 'd86c828583c5c6160e8acfee88ba1590';
    private $refreshToken = '3692bfa45759a67d83aedf0045f6cb63';

    private function setupStub($expectedMethod, $expectedUri, $expectedParameters, $expectedHeaders, $expectedReturn)
    {
        $stub = $this->createPartialMock(SpotifyWebAPI\Request::class, ['account']);

        $stub->expects($this->once())
            ->method('account')
            ->with(
                $this->equalTo($expectedMethod),
                $this->equalTo($expectedUri),
                $this->equalTo($expectedParameters),
                $this->equalTo($expectedHeaders)
            )
            ->willReturn($expectedReturn);

        return $stub;
    }

    public function testGenerateState()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI);

        $state = $session->generateState();

        $this->assertEquals(16, strlen($state));
        $this->assertIsString($state);
    }

    public function testGetAuthorizeUrl()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI);

        $state = 'state_value';
        $url = $session->getAuthorizeUrl([
            'scope' => ['playlist-modify-public', 'user-read-email'],
            'state' => $state,
        ]);

        $this->assertStringContainsString('client_id=' . $this->clientID, $url);
        $this->assertStringContainsString('redirect_uri=' . urlencode($this->redirectURI), $url);
        $this->assertStringContainsString('response_type=code', $url);
        $this->assertStringContainsString('scope=playlist-modify-public+user-read-email', $url);
        $this->assertStringContainsString('state=' . $state, $url);
        $this->assertStringContainsString('https://accounts.spotify.com/authorize', $url);
    }

    public function testGetAuthorizeUrlPkce()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, '', $this->redirectURI);

        $verifier = $session->generateCodeVerifier(64);
        $challenge = $session->generateCodeChallenge($verifier);
        $state = 'state_value';
        $url = $session->getAuthorizeUrl([
            'code_challenge' => $challenge,
            'scope' => ['playlist-modify-public', 'user-read-email'],
            'state' => $state,
        ]);

        $this->assertEquals(64, strlen($verifier));
        $this->assertIsString($challenge);

        $this->assertStringContainsString('client_id=' . $this->clientID, $url);
        $this->assertStringContainsString('redirect_uri=' . urlencode($this->redirectURI), $url);
        $this->assertStringContainsString('response_type=code', $url);
        $this->assertStringContainsString('scope=playlist-modify-public+user-read-email', $url);
        $this->assertStringContainsString('state=' . $state, $url);
        $this->assertStringContainsString('https://accounts.spotify.com/authorize', $url);
        $this->assertStringContainsString('code_challenge=' . $challenge, $url);
        $this->assertStringContainsString('code_challenge_method=S256', $url);
    }

    public function testGetClientId()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI);
        $expected = $this->clientID;

        $session->setClientId($expected);

        $this->assertEquals($expected, $session->getClientId());
    }

    public function testGetClientSecret()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI);
        $expected = $this->clientSecret;

        $session->setClientSecret($expected);

        $this->assertEquals($expected, $session->getClientSecret());
    }

    public function testGetRedirectUri()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI);
        $expected = $this->redirectURI;

        $session->setRedirectUri($expected);

        $this->assertEquals($expected, $session->getRedirectUri());
    }

    public function testRefreshAccessToken()
    {
        $expected = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
        ];

        $headers = [
            'Authorization' => 'Basic Yjc3NzI5MmFmMGRlZjIyZjkyNTc5OTFmYzc3MGI1MjA6NmEwNDE5ZjQzZDBhYTkzYjJhZTg4MTQyOWI2YjliYzI=',
        ];

        $return = [
            'body' => get_fixture('refresh-token'),
        ];

        $stub = $this->setupStub(
            'POST',
            '/api/token',
            $expected,
            $headers,
            $return
        );

        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI, $stub);
        $session->refreshAccessToken($this->refreshToken);

        $this->assertNotEmpty($session->getAccessToken());
        $this->assertNotEmpty($session->getRefreshToken());
        $this->assertEquals(time() + 3600, $session->getTokenExpiration());
        $this->assertEquals(['user-follow-read', 'user-follow-modify'], $session->getScope());
    }

    public function testRefreshAccessTokenNoClientSecret()
    {
        $expected = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
        ];

        $return = [
            'body' => get_fixture('refresh-token'),
        ];

        $stub = $this->setupStub(
            'POST',
            '/api/token',
            $expected,
            [],
            $return
        );

        $session = new SpotifyWebAPI\Session($this->clientID, null, $this->redirectURI, $stub);
        $session->refreshAccessToken($this->refreshToken);

        $this->assertNotEmpty($session->getAccessToken());
        $this->assertNotEmpty($session->getRefreshToken());
        $this->assertEquals(time() + 3600, $session->getTokenExpiration());
        $this->assertEquals(['user-follow-read', 'user-follow-modify'], $session->getScope());
    }

    public function testRefreshAccessTokenExistingToken()
    {
        $expected = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
        ];

        $headers = [
            'Authorization' => 'Basic Yjc3NzI5MmFmMGRlZjIyZjkyNTc5OTFmYzc3MGI1MjA6NmEwNDE5ZjQzZDBhYTkzYjJhZTg4MTQyOWI2YjliYzI=',
        ];

        $return = [
            'body' => get_fixture('refresh-token'),
        ];

        $stub = $this->setupStub(
            'POST',
            '/api/token',
            $expected,
            $headers,
            $return
        );

        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI, $stub);
        $session->setRefreshToken($this->refreshToken);
        $session->refreshAccessToken();

        $this->assertNotEmpty($session->getAccessToken());
        $this->assertNotEmpty($session->getRefreshToken());
        $this->assertEquals(time() + 3600, $session->getTokenExpiration());
        $this->assertEquals(['user-follow-read', 'user-follow-modify'], $session->getScope());
    }

    public function testRefreshAccessTokenNoReturnedToken()
    {
        $refreshToken = 'refresh-token';
        $expected = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        $headers = [
            'Authorization' => 'Basic Yjc3NzI5MmFmMGRlZjIyZjkyNTc5OTFmYzc3MGI1MjA6NmEwNDE5ZjQzZDBhYTkzYjJhZTg4MTQyOWI2YjliYzI=',
        ];

        $return = [
            'body' => get_fixture('refresh-token-no-refresh-token'),
        ];

        $stub = $this->setupStub(
            'POST',
            '/api/token',
            $expected,
            $headers,
            $return
        );

        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI, $stub);
        $session->setRefreshToken($this->refreshToken);
        $session->refreshAccessToken($refreshToken);

        $this->assertEquals($session->getRefreshToken(), $this->refreshToken);
    }

    public function testRefreshAccessTokenNoPreviousToken()
    {
        $expected = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
        ];

        $headers = [
            'Authorization' => 'Basic Yjc3NzI5MmFmMGRlZjIyZjkyNTc5OTFmYzc3MGI1MjA6NmEwNDE5ZjQzZDBhYTkzYjJhZTg4MTQyOWI2YjliYzI=',
        ];

        $return = [
            'body' => get_fixture('refresh-token-no-refresh-token'),
        ];

        $stub = $this->setupStub(
            'POST',
            '/api/token',
            $expected,
            $headers,
            $return
        );

        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI, $stub);
        $session->refreshAccessToken($this->refreshToken);

        $this->assertEquals($session->getRefreshToken(), $this->refreshToken);
    }

    public function testRequestAccessToken()
    {
        $authorizationCode = 'd1e893a80f79d9ab5e7d322ed922da540964a63c';
        $expected = [
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectURI,
        ];

        $return = [
            'body' => get_fixture('access-token'),
        ];

        $stub = $this->setupStub(
            'POST',
            '/api/token',
            $expected,
            [],
            $return
        );

        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI, $stub);
        $result = $session->requestAccessToken($authorizationCode);

        $this->assertTrue($result);
        $this->assertNotEmpty($session->getAccessToken());
        $this->assertNotEmpty($session->getRefreshToken());
        $this->assertEquals(time() + 3600, $session->getTokenExpiration());
        $this->assertEquals(['user-follow-read', 'user-follow-modify', 'user-library-read', 'user-library-modify'], $session->getScope());
    }

    public function testRequestAccessTokenPkce()
    {
        $authorizationCode = 'd1e893a80f79d9ab5e7d322ed922da540964a63c';
        $verifier = 'e15436a2bba525b651c2c6f6295a21045e718b5c';
        $expected = [
            'client_id' => $this->clientID,
            'code_verifier' => $verifier,
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectURI,
        ];

        $return = [
            'body' => get_fixture('access-token'),
        ];

        $stub = $this->setupStub(
            'POST',
            '/api/token',
            $expected,
            [],
            $return
        );

        $session = new SpotifyWebAPI\Session($this->clientID, '', $this->redirectURI, $stub);
        $result = $session->requestAccessToken($authorizationCode, $verifier);

        $this->assertTrue($result);
        $this->assertNotEmpty($session->getAccessToken());
        $this->assertNotEmpty($session->getRefreshToken());
        $this->assertEquals(time() + 3600, $session->getTokenExpiration());
        $this->assertEquals(['user-follow-read', 'user-follow-modify', 'user-library-read', 'user-library-modify'], $session->getScope());
    }

    public function testRequestCredentialsToken()
    {
        $expected = [
            'grant_type' => 'client_credentials',
        ];

        $headers = [
            'Authorization' => 'Basic Yjc3NzI5MmFmMGRlZjIyZjkyNTc5OTFmYzc3MGI1MjA6NmEwNDE5ZjQzZDBhYTkzYjJhZTg4MTQyOWI2YjliYzI=',
        ];

        $return = [
            'body' => get_fixture('access-token'),
        ];

        $stub = $this->setupStub(
            'POST',
            '/api/token',
            $expected,
            $headers,
            $return
        );

        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI, $stub);
        $result = $session->requestCredentialsToken();

        $this->assertTrue($result);
        $this->assertNotEmpty($session->getAccessToken());
        $this->assertEquals(time() + 3600, $session->getTokenExpiration());
    }

    public function testSetAccessToken()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI);
        $expected = $this->accessToken;

        $session->setAccessToken($expected);

        $this->assertEquals($expected, $session->getAccessToken());
    }

    public function testSetClientId()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI);
        $expected = $this->clientID;

        $session->setClientId($expected);

        $this->assertEquals($expected, $session->getClientId());
    }

    public function testSetClientSecret()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI);
        $expected = $this->clientSecret;

        $session->setClientSecret($expected);

        $this->assertEquals($expected, $session->getClientSecret());
    }

    public function testSetRedirectUri()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI);
        $expected = $this->redirectURI;

        $session->setRedirectUri($expected);

        $this->assertEquals($expected, $session->getRedirectUri());
    }

    public function testSetRefreshToken()
    {
        $session = new SpotifyWebAPI\Session($this->clientID, $this->clientSecret, $this->redirectURI);
        $expected = $this->refreshToken;

        $session->setRefreshToken($expected);

        $this->assertEquals($expected, $session->getRefreshToken());
    }
}
