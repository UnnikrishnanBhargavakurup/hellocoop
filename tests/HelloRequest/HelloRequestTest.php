<?php

namespace HelloCoop\Tests\HelloRequest;

use PHPUnit\Framework\TestCase;
use HelloCoop\HelloRequest\HelloRequest;

class HelloRequestTest extends TestCase
{
    private HelloRequest $request;

    protected function setUp(): void
    {
        $this->request = new HelloRequest();
    }

    public function testFetchReturnsGetParameter(): void
    {
        $_GET['test'] = 'value';
        $this->assertSame('value', $this->request->fetch('test'));
    }

    public function testFetchReturnsPostParameterIfGetNotSet(): void
    {
        unset($_GET['test']);
        $_POST['test'] = 'value';
        $this->assertSame('value', $this->request->fetch('test'));
    }

    public function testFetchReturnsDefaultIfNeitherGetNorPostIsSet(): void
    {
        unset($_GET['test'], $_POST['test']);
        $this->assertSame('default', $this->request->fetch('test', 'default'));
    }

    public function testFetchMultipleReturnsCorrectValues(): void
    {
        $_GET = ['key1' => 'value1'];
        $_POST = ['key2' => 'value2'];

        $result = $this->request->fetchMultiple(['key1', 'key2', 'key3']);

        $this->assertSame([
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => null,
        ], $result);
    }

    public function testFetchHeaderReturnsHeaderValue(): void
    {
        $_SERVER['HTTP_TEST_HEADER'] = 'HeaderValue';

        $this->assertSame('HeaderValue', $this->request->fetchHeader('Test-Header'));
    }

    public function testFetchHeaderReturnsDefaultIfHeaderNotSet(): void
    {
        unset($_SERVER['HTTP_TEST_HEADER']);

        $this->assertSame('default', $this->request->fetchHeader('Test-Header', 'default'));
    }

    public function testGetCookieReturnsCookieValue(): void
    {
        $_COOKIE['test_cookie'] = 'cookie_value';

        $this->assertSame('cookie_value', $this->request->getCookie('test_cookie'));
    }

    public function testGetCookieReturnsNullIfCookieNotSet(): void
    {
        unset($_COOKIE['test_cookie']);

        $this->assertNull($this->request->getCookie('test_cookie'));
    }
}
