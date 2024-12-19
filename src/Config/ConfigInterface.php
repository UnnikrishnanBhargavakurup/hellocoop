<?php

namespace HelloCoop\Config;

interface ConfigInterface
{
    public function getProduction(): bool;
    public function getSameSiteStrict(): ?bool;
    /**  @return array<string, int|string>|null */
    public function getError(): ?array;
    /**  @return array<string, string>|null */
    public function getScope(): ?array;
    /**  @return array<string>|null */
    public function getProviderHint(): ?array;
    /**  @return array<string, string> */
    public function getRoutes(): array;
    /**  @return array<string, string> */
    public function getCookies(): array;
    public function getLoginSync(): ?callable;
    public function getLogoutSync(): ?callable;
    public function getCookieToken(): ?bool;
    public function getApiRoute(): string;
    public function getAuthApiRoute(): string;
    public function getLoginApiRoute(): string;
    public function getLogoutApiRoute(): string;
    public function getClientId(): ?string;
    public function getHost(): string;
    public function getRedirectURI(): ?string;
    public function getHelloDomain(): string;
    public function getHelloWallet(): ?string;
    public function getSecret(): ?string;
    public function getLogDebug(): ?bool;
}
