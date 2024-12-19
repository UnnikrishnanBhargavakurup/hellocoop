<?php

namespace HelloCoop\Tests\Handler;

use PHPUnit\Framework\TestCase;
use HelloCoop\Handler\Callback;
use HelloCoop\Exception\CallbackException;
use HelloCoop\Tests\Traits\ServiceMocksTrait;

class CallbackTest extends TestCase
{
    use ServiceMocksTrait;

    private Callback $callback;
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpServiceMocks();
        // Create instance of Callback
        $this->callback = new Callback(
            $this->helloRequestMock,
            $this->helloResponseMock,
            $this->configMock
        );

        $this->replaceLazyLoadedProperty($this->callback, 'tokenFetcher', $this->tokenFetcherMock);
        $this->replaceLazyLoadedProperty($this->callback, 'tokenParser', $this->tokenParserMock);
        $this->tokenFetcherMock->method('fetchToken')->willReturn('valid_token_id');
    }

    public function testHandleCallbackSuccessfulLogin(): void
    {
        $_COOKIE['oidcName'] = $this->crypto->encrypt([
            'code_verifier' => 'test_verifier',
            'nonce' => 'valid_nonce',
            'redirect_uri' => 'https://example.com/callback',
            'target_uri' => '/dashboard'
        ]);

        $_GET = array_merge($_GET, [
            'code' => 'hello',  // Missing code
            'error' => null,
            'same_site' => 'Strict',
            'wildcard_domain' => 'example.com',
            'app_name' => 'MyApp'
        ]);

        $this->tokenParserMock->method('parseToken')->willReturn([
            'payload' => [
                'aud' => 'valid_client_id',
                'nonce' => 'valid_nonce',
                'sub' => 'user_sub',
                'iat' => time(),
                'exp' => time() + 3600
            ]
        ]);

        // Set up mock behaviors
        $this->configMock->method('getSameSiteStrict')->willReturn(false);
        $this->configMock->method('getHelloWallet')->willReturn('valid_wallet');
        $this->configMock->method('getApiRoute')->willReturn('http://api.example.com');
        $this->configMock->method('getRoutes')->willReturn(['loggedIn' => '/home']);

        // Call the method under test
        $result = $this->callback->handleCallback();

        // Assert that the result is the expected target URI
        $this->assertEquals('http://api.example.com?uri=example.com&appName=MyApp&redirectURI=https%3A%2F%2Fexample.com%2Fcallback&targetURI=%2Fdashboard&wildcard_console=true', $result);
    }

    public function testHandleCallbackMissingCode(): void
    {
        $_COOKIE['oidcName'] = $this->crypto->encrypt([
            'code_verifier' => 'test_verifier',
            'nonce' => 'test_nonce',
            'redirect_uri' => 'https://example.com/callback',
            'target_uri' => '/dashboard'
        ]);
        // Set up mock behaviors
        $_GET = array_merge($_GET, [
            'code' => null,  // Missing code
            'error' => null,
            'same_site' => 'Strict',
            'wildcard_domain' => 'example.com',
            'app_name' => 'MyApp'
        ]);


        $this->assertEquals('/dashboard?error=invalid_request&error_description=Missing+code+parameter', $this->callback->handleCallback());
    }

    public function testHandleCallbackInvalidTokenAudience(): void
    {
        // Set up mock behaviors
        $_GET = array_merge($_GET, [
            'code' => 'hello',
            'error' => null,
            'same_site' => 'Strict',
            'wildcard_domain' => 'example.com',
            'app_name' => 'MyApp'
        ]);

        $_COOKIE['oidcName'] = $this->crypto->encrypt([
            'code_verifier' => 'test_verifier',
            'nonce' => 'test_nonce',
            'redirect_uri' => 'https://example.com/callback',
            'target_uri' => '',
        ]);

        $this->tokenParserMock->method('parseToken')->willReturn([
            'payload' => [
                'aud' => 'invalid_client_id', //Invalid Token Audience
                'nonce' => 'valid_nonce',
                'sub' => 'user_sub',
                'iat' => time(),
                'exp' => time() + 3600
            ]
        ]);

        $this->configMock->method('getSameSiteStrict')->willReturn(true);
        $this->configMock->method('getHelloWallet')->willReturn('valid_wallet');
        $this->configMock->method('getApiRoute')->willReturn('http://api.example.com');
        $this->configMock->method('getRoutes')->willReturn(['loggedIn' => '/home']);

        // Expect CallbackException for invalid audience
        $this->expectException(CallbackException::class);
        $this->expectExceptionMessage('Wrong ID token audience.');

        // Call the method under test
        $this->callback->handleCallback();
    }
}
