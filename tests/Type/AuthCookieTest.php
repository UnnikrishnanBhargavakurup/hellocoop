<?php

namespace HelloCoop\Tests\Type;

use PHPUnit\Framework\TestCase;
use HelloCoop\Type\AuthCookie;
use InvalidArgumentException;

class AuthCookieTest extends TestCase
{
    public function testConstructorInitializesProperties(): void
    {
        $sub = 'user123';
        $iat = time();

        $authCookie = new AuthCookie($sub, $iat);

        $this->assertSame($sub, $authCookie->sub);
        $this->assertSame($iat, $authCookie->iat);
    }

    public function testSetAndGetExtraProperty(): void
    {
        $authCookie = new AuthCookie('user123', time());

        $authCookie->setExtraProperty('key1', 'value1');
        $authCookie->setExtraProperty('key2', 123);

        $this->assertSame('value1', $authCookie->getExtraProperty('key1'));
        $this->assertSame(123, $authCookie->getExtraProperty('key2'));
        $this->assertNull($authCookie->getExtraProperty('nonexistent_key'));
    }

    public function testFromArrayCreatesInstanceWithValidData(): void
    {
        $data = [
            'sub' => 'user123',
            'iat' => time(),
            'custom1' => 'value1',
            'custom2' => 456
        ];

        $authCookie = AuthCookie::fromArray($data);

        $this->assertSame('user123', $authCookie->sub);
        $this->assertSame($data['iat'], $authCookie->iat);
        $this->assertSame('value1', $authCookie->getExtraProperty('custom1'));
        $this->assertSame(456, $authCookie->getExtraProperty('custom2'));
    }

    public function testFromArrayThrowsExceptionForMissingKeys(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required keys "sub" or "iat".');

        $data = ['sub' => 'user123']; // Missing 'iat'
        AuthCookie::fromArray($data);
    }
}
