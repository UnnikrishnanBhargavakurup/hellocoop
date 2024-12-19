<?php

namespace HelloCoop\Type;

use InvalidArgumentException;

class OIDC
{
    public string $codeVerifier;
    public string $nonce;
    public string $redirectUri;
    public string $targetUri;

    public function __construct(string $codeVerifier, string $nonce, string $redirectUri, string $targetUri)
    {
        $this->codeVerifier = $codeVerifier;
        $this->nonce = $nonce;
        $this->redirectUri = $redirectUri;
        $this->targetUri = $targetUri;
    }

    /**
     * @param array<string, string> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['code_verifier'])) {
            throw new InvalidArgumentException('Missing code_verifier');
        }

        if (!isset($data['nonce'])) {
            throw new InvalidArgumentException('Missing nonce');
        }

        if (!isset($data['redirect_uri'])) {
            throw new InvalidArgumentException('Missing redirect_uri');
        }

        if (!isset($data['target_uri'])) {
            throw new InvalidArgumentException('Missing target_uri');
        }

        return new self(
            $data['code_verifier'],
            $data['nonce'],
            $data['redirect_uri'],
            $data['target_uri']
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'code_verifier' => $this->codeVerifier,
            'nonce' => $this->nonce,
            'redirect_uri' => $this->redirectUri,
            'target_uri' => $this->targetUri,
        ];
    }
}
