<?php

namespace HelloCoop\Tests\Lib;

use PHPUnit\Framework\TestCase;
use HelloCoop\Lib\TokenParser;

class TokenParserTest extends TestCase
{
    protected $tokenParser;

    protected function setUp(): void
    {
        // Initialize the PKCE instance here
        $this->tokenParser = new TokenParser();
    }
    public function testParseTokenSuccess(): void
    {

        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwiZXhwIjoxNjU0MjA4ODAwfQ.sflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $result = $this->tokenParser->parseToken($token);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('payload', $result);
        $this->assertArrayHasKey('sub', $result['payload']);
        $this->assertArrayHasKey('exp', $result['payload']);
    }

    public function testParseTokenInvalidJson(): void
    {
        $invalidToken = 'invalid.token.format';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to parse token');

        $this->tokenParser->parseToken($invalidToken);
    }

    public function testParseTokenInvalidFormat(): void
    {
        $invalidToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid token format.');

        $this->tokenParser->parseToken($invalidToken);
    }
}
