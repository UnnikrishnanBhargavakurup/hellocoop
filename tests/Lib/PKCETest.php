<?php

namespace HelloCoop\Tests\Lib;

use HelloCoop\Lib\PKCE;
use PHPUnit\Framework\TestCase;

class PKCETest extends TestCase
{
    /** @var PKCE */
    protected $pkce;

    protected function setUp(): void
    {
        // Initialize the PKCE instance here
        $this->pkce = new PKCE();
    }

    /** @test */
    public function testGeneratesAVerifierOfCorrectLength(): void
    {
        // Test the default length of the verifier
        $verifier = $this->pkce->generateVerifier();
        $this->assertEquals(PKCE::VERIFIER_LENGTH, strlen($verifier));

        // Test a custom length
        $customLength = 50;
        $verifier = $this->pkce->generateVerifier($customLength);
        $this->assertEquals($customLength, strlen($verifier));
    }

    /** @test */
    public function testGeneratesAValidCodeChallenge(): void
    {
        // Generate a verifier and its corresponding challenge
        $verifier = $this->pkce->generateVerifier();
        $challenge = $this->pkce->generateChallenge($verifier);

        // Validate that the challenge is a string and matches the expected format
        $this->assertIsString($challenge);
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9_-]+$/', $challenge);
    }

    /** @test */
    public function testGeneratesPkceChallengePair(): void
    {
        $pkcePair = $this->pkce->generatePkce();

        // Validate that the pair contains 'code_verifier' and 'code_challenge' keys
        $this->assertArrayHasKey('code_verifier', $pkcePair);
        $this->assertArrayHasKey('code_challenge', $pkcePair);

        $this->assertIsString($pkcePair['code_verifier']);
        $this->assertIsString($pkcePair['code_challenge']);
    }

    /** @test */
    public function testVerifiesTheCorrectChallenge(): void
    {
        // Generate a verifier and a challenge pair
        $pkcePair = $this->pkce->generatePkce();
        $verifier = $pkcePair['code_verifier'];
        $expectedChallenge = $pkcePair['code_challenge'];

        // Test that the challenge is correct
        $this->assertTrue($this->pkce->verifyChallenge($verifier, $expectedChallenge));

        // Test with an incorrect challenge
        $incorrectChallenge = 'invalid_challenge';
        $this->assertFalse($this->pkce->verifyChallenge($verifier, $incorrectChallenge));
    }

    /** @test */
    public function testGeneratesACodeVerifierAndChallengePair(): void
    {
        // Generate the code verifier and challenge pair
        $pkcePair = $this->pkce->generate();

        // Validate that the pair contains the expected keys and types
        $this->assertArrayHasKey('code_verifier', $pkcePair);
        $this->assertArrayHasKey('code_challenge', $pkcePair);

        $this->assertIsString($pkcePair['code_verifier']);
        $this->assertIsString($pkcePair['code_challenge']);
    }
}
