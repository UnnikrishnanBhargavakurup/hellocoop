<?php

namespace HelloCoop\Tests\Utils;

use PHPUnit\Framework\TestCase;
use HelloCoop\Utils\QueryParamFetcher;

class QueryParamFetcherTest extends TestCase
{
    protected array $originalGet;
    protected function setUp(): void
    {
        // Backup the original $_GET superglobal
        $this->originalGet = $_GET;
    }

    protected function tearDown(): void
    {
        // Restore the original $_GET superglobal
        $_GET = $this->originalGet;
    }

    public function testFetchWithExistingKeys(): void
    {
        $_GET = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        $result = QueryParamFetcher::fetch(['key1', 'key2']);

        $this->assertEquals([
            'key1' => 'value1',
            'key2' => 'value2',
        ], $result);
    }

    public function testFetchWithNonExistentKeys(): void
    {
        $_GET = [
            'key1' => 'value1',
        ];

        $result = QueryParamFetcher::fetch(['key2', 'key3']);

        $this->assertEquals([
            'key2' => null,
            'key3' => null,
        ], $result);
    }

    public function testFetchWithMixedKeys(): void
    {
        $_GET = [
            'key1' => 'value1',
            'key3' => 'value3',
        ];

        $result = QueryParamFetcher::fetch(['key1', 'key2', 'key3']);

        $this->assertEquals([
            'key1' => 'value1',
            'key2' => null,
            'key3' => 'value3',
        ], $result);
    }
}
