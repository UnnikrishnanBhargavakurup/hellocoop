<?php

namespace HelloCoop\Handler;

use HelloCoop\HelloResponse\HelloResponseInterface;
use HelloCoop\HelloRequest\HelloRequestInterface;
use HelloCoop\Config\ConfigInterface;
use HelloCoop\Lib\OIDCManager;
use HelloCoop\Type\OIDC;
use HelloCoop\Lib\AuthHelper;
use HelloCoop\Lib\Crypto;
use HelloCoop\Lib\PKCE;
use RuntimeException;

class Login
{
    private HelloResponseInterface $helloResponse;
    private HelloRequestInterface $helloRequest;
    private ConfigInterface $config;
    private OIDCManager $oidcManager;
    private AuthHelper $authHelper;

    /** @var array<string>  */
    private array $redirectURIs;

    /**
     * @param HelloRequestInterface $helloRequest
     * @param HelloResponseInterface $helloResponse
     * @param ConfigInterface $config
     * @param array<string> $redirectURIs
     */
    public function __construct(
        HelloRequestInterface $helloRequest,
        HelloResponseInterface $helloResponse,
        ConfigInterface $config,
        array $redirectURIs = []
    ) {
        $this->helloRequest = $helloRequest;
        $this->helloResponse = $helloResponse;
        $this->config = $config;
        $this->redirectURIs = $redirectURIs;
    }

    private function getOIDCManager(): OIDCManager
    {
        return $this->oidcManager ??= new OIDCManager(
            $this->helloRequest,
            $this->helloResponse,
            $this->config,
            new Crypto($this->config->getSecret())
        );
    }

    private function getAuthHelper(): AuthHelper
    {
        return $this->authHelper ??= new AuthHelper(new PKCE());
    }

    public function generateLoginUrl(): ?string
    {
        $params = $this->helloRequest->fetchMultiple([
            'provider_hint',
            'scope',
            'target_uri',
            'redirect_uri',
            'nonce',
            'prompt',
            'login_hint',
            'domain_hint'
        ]);

        if (empty($this->config->getClientId())) {
            throw new RuntimeException('Missing HELLO_CLIENT_ID configuration');
        }

        $redirectURI = $this->config->getRedirectURI();
        $host = $this->helloRequest->fetchHeader('Host');

        if (empty($redirectURI)) {
            if (isset($this->redirectURIs[$host])) {
                $redirectURI = $this->redirectURIs[$host];
            } elseif (!empty($params['redirect_uri'])) {
                $this->redirectURIs[$host] = $redirectURI = $params['redirect_uri'];
                error_log("Hello: RedirectURI for $host => $redirectURI");
            } else {
                error_log('Hello: Discovering API RedirectURI route ...');
                //TODO: Implement bounce logic if needed
                throw new RuntimeException('RedirectURI not found');
            }
        }

        $providerHintString = $params['provider_hint'];
        $providerHint = $providerHintString ? array_map('trim', explode(' ', $providerHintString)) : null;

        $scopeString = $params['scope'];
        $scope = $scopeString ? array_map('trim', explode(' ', $scopeString)) : null;

        $request = [
            'redirect_uri' => $redirectURI,
            'client_id' => $this->config->getClientId(),
            'wallet' => $this->config->getHelloWallet(),
            'scope' => $scope,
            'provider_hint' => $providerHint,
            'login_hint' => $params['login_hint'] ?? null,
            'domain_hint' => $params['domain_hint'] ?? null,
            'prompt' => $params['prompt'] ?? null,
        ];

        if (!empty($params['nonce'])) {
            $request['nonce'] = $params['nonce'];
        }

        $authResponse = $this->getAuthHelper()->createAuthRequest($request);

        $this->getOIDCManager()->saveOidc(OIDC::fromArray([
            'nonce' => $authResponse['nonce'],
            'code_verifier' => $authResponse['code_verifier'],
            'redirect_uri' => $redirectURI,
            'target_uri' => $params['target_uri'],
        ]));

        return $authResponse['url'];
    }
}
