<?php

namespace HelloCoop\Tests\Type;

use HelloCoop\Type\Auth;
use HelloCoop\Type\AuthCookie;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public function testConstructorInitializesProperties(): void
    {
        $authCookie = new AuthCookie('user123', time());
        $authCookie->setExtraProperty('role', 'admin');

        $auth = new Auth(true, $authCookie, 'token123');

        $this->assertTrue($auth->isLoggedIn);
        $this->assertSame($authCookie, $auth->authCookie);
        $this->assertSame('token123', $auth->cookieToken);
    }

    public function testConstructorWithNullValues(): void
    {
        $auth = new Auth(false);

        $this->assertFalse($auth->isLoggedIn);
        $this->assertNull($auth->authCookie);
        $this->assertNull($auth->cookieToken);
    }

    public function testAuthCookieProperties(): void
    {
        $authCookie = new AuthCookie('user123', time());
        $authCookie->setExtraProperty('role', 'admin');

        $auth = new Auth(true, $authCookie);

        $this->assertSame('user123', $auth->authCookie->sub);
        $this->assertSame('admin', $auth->authCookie->getExtraProperty('role'));
    }

    public function testAuthWithoutCookieToken(): void
    {
        $auth = new Auth(true);

        $this->assertTrue($auth->isLoggedIn);
        $this->assertNull($auth->authCookie);
        $this->assertNull($auth->cookieToken);
    }
}
