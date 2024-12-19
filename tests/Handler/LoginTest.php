<?php

namespace HelloCoop\Tests\Handler;

use PHPUnit\Framework\TestCase;
use HelloCoop\Handler\Login;
use HelloCoop\Tests\Traits\ServiceMocksTrait;
use HelloCoop\Config\ConfigInterface;
use RuntimeException;

class LoginTest extends TestCase
{
    use ServiceMocksTrait;

    private Login $login;
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpServiceMocks();

        $this->configMock = $this->createMock(ConfigInterface::class);
        $this->configMock->method('getSecret')
        ->willReturn('1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef');

        $this->configMock->method('getCookies')
        ->willReturn([
            'authName' => 'authName',
            'oidcName' => 'oidcName',
        ]);

        $this->login = new Login(
            $this->helloRequestMock,
            $this->helloResponseMock,
            $this->configMock,
            ['example.com' => 'https://example.com/callback']
        );
    }

    public function testGenerateLoginUrlSuccess(): void
    {
        // Setup mocks
        $_GET = [
            'provider_hint' => 'google',
            'scope' => 'openid profile',
            'target_uri' => 'https://example.com/target',
            'redirect_uri' => 'https://example.com/callback',
            'nonce' => '1234',
            'prompt' => 'consent',
            'login_hint' => 'user@example.com',
            'domain_hint' => 'example.com'
        ];

        $this->configMock
        ->method('getClientId')
        ->willReturn('valid_client_id');

        $this->helloRequestMock->method('fetchHeader')
        ->with('Host')
        ->willReturn('example.com');

        // Test the URL generation
        $url = $this->login->generateLoginUrl();

        $this->assertTrue(
            filter_var($url, FILTER_VALIDATE_URL) !== false,
            "The URL is not valid."
        );

        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $params);
        $this->assertEquals($params['provider_hint'], 'google');
        $this->assertEquals($params['scope'], 'openid profile');
        $this->assertEquals($params['code_challenge_method'], 'S256');
        $this->assertEquals($params['login_hint'], 'user@example.com');
        //$this->assertEquals('https://wallet.hello.coop/authorize?client_id=valid_client_id&redirect_uri=https%3A%2F%2Fexample.com%2Fcallback&scope=openid&response_type=code&response_mode=query&nonce=1234&prompt=consent&code_challenge=yyhvlwTA3oVJcnTpkLV70DjqXb794Ar5Sgth12qbRsM&code_challenge_method=S256&provider_hint=google&login_hint=user%40example.com&domain_hint=example.com', $url);
    }

    public function testGenerateLoginUrlMissingClientId(): void
    {
        $_GET = [
            'provider_hint' => 'google',
            'scope' => 'openid profile',
            'target_uri' => 'https://example.com/target',
            'redirect_uri' => 'https://example.com/callback',
            'nonce' => '1234',
            'prompt' => 'consent',
            'login_hint' => 'user@example.com',
            'domain_hint' => 'example.com'
        ];

        $this->configMock
        ->method('getClientId')
        ->willReturn(null);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Missing HELLO_CLIENT_ID configuration');

        $this->login->generateLoginUrl();
    }

    public function testGenerateLoginUrlMissingRedirectURI(): void
    {
        $_GET = [
            'provider_hint' => 'google',
            'scope' => 'openid profile',
            'target_uri' => 'https://example.com/target',
            'redirect_uri' => null
        ];

        $this->configMock
        ->method('getClientId')
        ->willReturn('valid_client_id');

        $this->helloRequestMock->method('fetchHeader')
        ->with('Host')
        ->willReturn('example2.com');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('RedirectURI not found');

        $this->login->generateLoginUrl();
    }
}
