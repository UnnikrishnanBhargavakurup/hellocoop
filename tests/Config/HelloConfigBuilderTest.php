<?php

namespace HelloCoop\Tests\Config;

use HelloCoop\Config\HelloConfig;
use HelloCoop\Config\HelloConfigBuilder;
use PHPUnit\Framework\TestCase;

class HelloConfigBuilderTest extends TestCase
{
    public function testHelloConfigBuilderBuildsConfigCorrectly(): void
    {
        // Arrange: Create the builder and set values
        $builder = new HelloConfigBuilder();
        $config = $builder
            ->setApiRoute('https://api.example.com')
            ->setAuthApiRoute('https://auth.example.com')
            ->setLoginApiRoute('/login')
            ->setLogoutApiRoute('/logout')
            ->setSameSiteStrict(true)
            ->setClientId('test-client-id')
            ->setRedirectURI('https://example.com/callback')
            ->setHost('example.com')
            ->setSecret('secret-key')
            ->setCookies(['authName' => 'custom_auth', 'oidcName' => 'custom_oidc'])
            ->setProduction(false)
            ->setHelloDomain('custom.hello.coop')
            ->setHelloWallet('custom-wallet')
            ->setScope(['openid', 'profile', 'email'])
            ->setProviderHint(['google', 'facebook'])
            ->setRoutes([
                'loggedIn' => '/dashboard',
                'loggedOut' => '/goodbye',
                'error' => '/oops',
            ])
            ->setLoginSync(fn() => 'login_sync')
            ->setLogoutSync(fn() => 'logout_sync')
            ->setCookieToken(true)
            ->setLogDebug(true)
            ->setError(['error_code' => 123, 'message' => 'Something went wrong'])
            ->build();

        // Assert: Verify the object is correctly built
        $this->assertInstanceOf(HelloConfig::class, $config);
        $this->assertEquals('https://api.example.com', $config->getApiRoute());
        $this->assertEquals('https://auth.example.com', $config->getAuthApiRoute());
        $this->assertEquals('/login', $config->getLoginApiRoute());
        $this->assertEquals('/logout', $config->getLogoutApiRoute());
        $this->assertTrue($config->getSameSiteStrict());
        $this->assertEquals('test-client-id', $config->getClientId());
        $this->assertEquals('https://example.com/callback', $config->getRedirectURI());
        $this->assertEquals('example.com', $config->getHost());
        $this->assertEquals('secret-key', $config->getSecret());
        $this->assertEquals(['authName' => 'custom_auth', 'oidcName' => 'custom_oidc'], $config->getCookies());
        $this->assertFalse($config->getProduction());
        $this->assertEquals('custom.hello.coop', $config->getHelloDomain());
        $this->assertEquals('custom-wallet', $config->getHelloWallet());
        $this->assertEquals(['openid', 'profile', 'email'], $config->getScope());
        $this->assertEquals(['google', 'facebook'], $config->getProviderHint());
        $this->assertEquals([
            'loggedIn' => '/dashboard',
            'loggedOut' => '/goodbye',
            'error' => '/oops',
        ], $config->getRoutes());
        $this->assertIsCallable($config->getLoginSync());
        $this->assertIsCallable($config->getLogoutSync());
        $this->assertTrue($config->getCookieToken());
        $this->assertTrue($config->getLogDebug());
        $this->assertEquals(['error_code' => 123, 'message' => 'Something went wrong'], $config->getError());
    }
}
