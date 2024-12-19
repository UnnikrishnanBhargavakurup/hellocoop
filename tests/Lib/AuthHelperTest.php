<?php

namespace Tests\HelloCoop\Lib;

use HelloCoop\Lib\AuthHelper;
use HelloCoop\Lib\PKCE;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AuthHelperTest extends TestCase
{
    private AuthHelper $authHelper;

    private $pkceMock;

    protected function setUp(): void
    {
        $this->pkceMock = $this->createMock(PKCE::class);
        $this->authHelper = new AuthHelper($this->pkceMock);
    }

    public function testCreateAuthRequestSuccess(): void
    {
        $this->pkceMock->method('generate')->willReturn([
            'code_challenge' => 'test-challenge',
            'code_verifier' => 'test-verifier'
        ]);


        $config = [
            'client_id' => 'test-client-id',
            'redirect_uri' => 'https://example.com/callback',
            'response_type' => 'code',
        ];

        $result = $this->authHelper->createAuthRequest($config);

        $this->assertArrayHasKey('url', $result);
        $this->assertEquals('test-verifier', $result['code_verifier']);
    }

    public function testMissingClientIdThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('client_id is required in the authorization request.');

        $this->authHelper->createAuthRequest([
            'redirect_uri' => 'https://example.com/callback'
        ]);
    }

    public function testInvalidScopeThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('One or more passed scopes are invalid.');

        $this->authHelper->createAuthRequest([
            'client_id' => 'test-client-id',
            'redirect_uri' => 'https://example.com/callback',
            'scope' => ['invalid-scope']
        ]);
    }

    public function testAddDefaultsToScopes(): void
    {
        $config = [
            'client_id' => 'test-client-id',
            'redirect_uri' => 'https://example.com/callback',
        ];

        $this->pkceMock->method('generate')->willReturn([
            'code_challenge' => 'test-challenge',
            'code_verifier' => 'test-verifier'
        ]);

        $result = $this->authHelper->createAuthRequest($config);
        $this->assertStringContainsString('openid', $result['url']);
    }

    private function mockStaticMethod($class, $method, $returnValue): void
    {
        $mock = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->onlyMethods([$method])
            ->getMock();

        $mock->method($method)->willReturn($returnValue);

        $refClass = new \ReflectionClass($class);
        $method = $refClass->getMethod('setMocked');
        $method->setAccessible(true);
    }
}
