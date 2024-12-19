<?php

namespace HelloCoop\Lib;

class PKCE
{
    public const VERIFIER_LENGTH = 43;

    /** Generate cryptographically strong random string
     * @param int $size The desired length of the string
     * @return string The random string
     */
    public function generateVerifier(int $size = self::VERIFIER_LENGTH): string
    {
        $mask = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._~";
        $result = "";
        $randomBytes = random_bytes(max(1, $size));

        // Loop through each byte to generate a random character from the mask
        for ($i = 0; $i < $size; $i++) {
            $randomIndex = ord($randomBytes[$i]) % strlen($mask);
            $result .= $mask[$randomIndex];
        }

        return $result;
    }

    /** Generate a PKCE code challenge from a code verifier
     * @param string $code_verifier
     * @return string The base64 url encoded code challenge
     */
    public function generateChallenge(string $code_verifier): string
    {
        // Create the SHA-256 hash of the verifier
        $hash = hash('sha256', $code_verifier, true);

        // Base64 URL encode the hash
        $encoded = base64_encode($hash);
        $encoded = rtrim($encoded, '=');
        return str_replace(['/', '+'], ['_', '-'], $encoded);
    }

    /** Generate a PKCE challenge pair
     * @param int $length Length of the verifier (between 43-128). Defaults to 43.
     * @return array<string, string> A PKCE challenge pair containing 'code_verifier' and 'code_challenge'
     */
    public function generatePkce(int $length = self::VERIFIER_LENGTH): array
    {
        $verifier = self::generateVerifier($length);
        $challenge = self::generateChallenge($verifier);

        return [
            'code_verifier' => $verifier,
            'code_challenge' => $challenge
        ];
    }

    /** Verify that a code_verifier produces the expected code challenge
     * @param string $code_verifier
     * @param string $expectedChallenge The code challenge to verify
     * @return bool True if challenges are equal. False otherwise.
     */
    public function verifyChallenge(string $code_verifier, string $expectedChallenge): bool
    {
        $actualChallenge = self::generateChallenge($code_verifier);
        return $actualChallenge === $expectedChallenge;
    }

    /** Generate a PKCE challenge pair
     * @return array<string, string>
     */
    public function generate(): array
    {
        $codeVerifier = self::generateVerifier();
        $codeChallenge = self::generateChallenge($codeVerifier);

        return [
            'code_verifier' => $codeVerifier,
            'code_challenge' => $codeChallenge
        ];
    }
}
