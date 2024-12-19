<?php

namespace HelloCoop\Lib;

use Exception;
use HelloCoop\Config\Constants;
use HelloCoop\Utils\CurlWrapper;

class TokenFetcher
{
    private const DEFAULT_ENDPOINT = '/oauth/token';
    private CurlWrapper $curl;

    public function __construct(CurlWrapper $curl)
    {
        $this->curl = $curl;
    }

    /**
     * @param array<string, string> $config
     * @return string
     * @throws Exception
     */
    public function fetchToken(array $config): string
    {
        $code = $config['code'];
        $codeVerifier = $config['code_verifier'];
        $clientId = $config['client_id'];
        $redirectUri = $config['redirect_uri'];
        $wallet = $config['wallet'] ?? Constants::$PRODUCTION_WALLET;

        $params = [
            'code' => $code,
            'code_verifier' => $codeVerifier,
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
        ];

        $body = http_build_query($params);
        $tokenEndpoint = $wallet . self::DEFAULT_ENDPOINT;

        try {
            $ch = $this->curl->init($tokenEndpoint);
            $this->curl->setOpt($ch, CURLOPT_POST, true);
            $this->curl->setOpt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            $this->curl->setOpt($ch, CURLOPT_POSTFIELDS, $body);
            $this->curl->setOpt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = $this->curl->exec($ch);
            $httpCode = $this->curl->getInfo($ch, CURLINFO_HTTP_CODE);

            if ($this->curl->error($ch)) {
                throw new \Exception('Curl error: ' . $this->curl->error($ch));
            }

            $this->curl->close($ch);

            /** @var array<string, mixed> $json */
            $json = json_decode($response, true);

            if ($httpCode !== 200) {
                $message = "Fetch $tokenEndpoint failed with $httpCode. " . ($json['error'] ?? '');
                throw new \Exception($message);
            }

            if (isset($json['error']) && is_string($json['error'])) {
                throw new \Exception($json['error']);
            }

            if (!isset($json['id_token']) || !is_string($json['id_token'])) {
                throw new \Exception('No id_token in response.');
            }

            return $json['id_token'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
