<?php

namespace HelloCoop\Lib;

use HelloCoop\Config\ConfigInterface;
use HelloCoop\Exception\CryptoFailedException;
use HelloCoop\Exception\DecryptionFailedException;
use HelloCoop\Type\OIDC;
use HelloCoop\HelloRequest\HelloRequestInterface;
use HelloCoop\HelloResponse\HelloResponseInterface;
use Exception;

class OIDCManager
{
    private Crypto $crypto;
    private ConfigInterface $config;
    private HelloResponseInterface $helloResponse;
    private HelloRequestInterface $helloRequest;

    public function __construct(
        HelloRequestInterface $helloRequest,
        HelloResponseInterface $helloResponse,
        ConfigInterface $config,
        Crypto $crypto
    ) {
        $this->helloRequest = $helloRequest;
        $this->helloResponse = $helloResponse;
        $this->config = $config;
        $this->crypto = $crypto;
    }

    /**
     * @throws DecryptionFailedException
     */
    public function getOidc(): ?OIDC
    {
        $oidcCookie = $this->helloRequest->getCookie($this->config->getCookies()['oidcName']);

        if (!$oidcCookie) {
            return null;
        }

        try {
            $oidcData = $this->crypto->decrypt($oidcCookie);
            if (is_array($oidcData)) {
                return OIDC::fromArray($oidcData);
            }
        } catch (Exception $e) {
            $this->clearOidcCookie();
            error_log($e->getMessage());
            throw $e;
            // TODO: Log error
        }

        return null;
    }

    /**
     * @throws CryptoFailedException
     */
    public function saveOidc(OIDC $oidc): void
    {
        try {
            $encCookie = $this->crypto->encrypt($oidc->toArray());
            $this->helloResponse->setCookie(
                $this->config->getCookies()['oidcName'],
                $encCookie,
                time() + 5 * 60, // 5 minutes
                $this->config->getApiRoute(),
                $this->config->getHost(), // This is required if we are behind a proxy.
                $this->config->getProduction(),
                true // HttpOnly
            );//TODO 'samesite' can be added if we use options instead of named parameters
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
            // TODO: Log error
        }
    }

    public function clearOidcCookie(): void
    {
        $this->helloResponse->deleteCookie(
            $this->config->getCookies()['oidcName'],
            $this->config->getApiRoute(),
            ''
        );
    }
}
