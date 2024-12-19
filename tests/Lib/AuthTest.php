<?php

namespace HelloCoop\Tests;

use PHPUnit\Framework\TestCase;
use HelloCoop\Lib\Auth;
use HelloCoop\Type\Auth as AuthType;
use HelloCoop\Tests\Traits\ServiceMocksTrait;

class AuthTest extends TestCase
{
    use ServiceMocksTrait;

    private Auth $auth;
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpServiceMocks();

        $this->auth = new Auth(
            $this->helloRequestMock,
            $this->helloResponseMock,
            $this->configMock,
        );
    }
    public function testSaveAuthCookieSuccess(): void
    {
        $authMock = AuthType::fromArray([
            'isLoggedIn' => true,
            'authCookie' => [
                'sub' => 'user123',
                'iat' => time(),
                'name' => 'Dick Hardt',
                'picture' => 'https://pictures.hello.coop/r/7a160eed-46bf-48e2-a909-161745535895.png',
                'email' => 'dick.hardt@hello.coop'
            ]
        ]);

        $this->helloResponseMock
            ->expects($this->once())
            ->method('setCookie')
            ->with('authName');

        $result = $this->auth->saveAuthCookie($authMock);
        $this->assertTrue($result);
    }
}
