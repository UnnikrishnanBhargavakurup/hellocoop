<?php

namespace HelloCoop\Tests\Handler;

use HelloCoop\Config\HelloConfig;
use HelloCoop\Lib\Auth;
use HelloCoop\Type\Auth as AuthType;
use PHPUnit\Framework\TestCase;
use HelloCoop\Handler\Invite;

class InviteTest extends TestCase
{
    private Invite $invite;
    private $configMock;
    private $authMock;

    public function setUp(): void
    {
        $this->configMock = $this->createMock(HelloConfig::class);
        $this->authMock = $this->createMock(Auth::class);
        $this->invite = new Invite($this->configMock, $this->authMock);
    }
    public function testCanGenerateInviteUrl(): void
    {
        // Mocking the dependencies for HelloConfig
        $this->configMock->method('getClientId')->willReturn('testClientId');
        $this->configMock->method('getRedirectURI')->willReturn('/redirect');
        $this->configMock->method('getHelloDomain')->willReturn('hello.com');

        // Mocking the dependencies for Auth
        $authMockData = $this->createMock(AuthType::class);
        $authMockData->method('toArray')->willReturn(['sub' => 'user123']);
        $this->authMock->method('getAuthfromCookies')->willReturn($authMockData);

        // Setting up the $_GET superglobal to mock the query parameters
        $_GET = [
            'target_uri' => 'https://example.com',
            'app_name' => 'TestApp',
            'prompt' => 'TestPrompt',
            'role' => 'admin',
            'tenant' => 'TestTenant',
            'state' => 'state123',
            'redirect_uri' => 'https://redirect.com'
        ];

        // Test the URL generation
        $url = $this->invite->generateInviteUrl();

        // Assert the URL is valid
        $this->assertTrue(
            filter_var($url, FILTER_VALIDATE_URL) !== false,
            "The URL is not valid."
        );

        // Define the expected URL
        $expectedUrl = "https://hello.com/invite?app_name=TestApp&prompt=TestPrompt&role=admin&tenant=TestTenant&state=state123&inviter=user123&client_id=testClientId&initiate_login_uri=%2Fredirect&return_uri=https%3A%2F%2Fexample.com";

        // Assert that the generated URL matches the expected one
        $this->assertSame($expectedUrl, $url);
    }
}
