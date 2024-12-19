<?php

namespace HelloCoop\Config;

class HelloConfig implements ConfigInterface
{
    private string $apiRoute;
    private string $authApiRoute;
    private string $loginApiRoute;
    private string $logoutApiRoute;
    private bool $sameSiteStrict;
    private ?string $clientId;

    /**
     * Include encrypted cookie in auth response
     * @var ?bool $cookieToken
     */
    private ?bool $cookieToken = null;
    private ?string $redirectURI;
    private string $helloDomain;
    private ?string $helloWallet = null;
    private string $host;
    private ?string $secret = null;
    private ?bool $logDebug = null;
    private ?array $error = null;
    private array $scope;
    private array $providerHint;
    private array $routes;
    private array $cookies;
    private $loginSync;
    private $logoutSync;
    private bool $production;

    public function __construct(
        string $apiRoute,
        string $authApiRoute,
        string $loginApiRoute,
        string $logoutApiRoute,
        bool $sameSiteStrict, //Restricts cross-site request sharing to prevent CSRF attacks.
        ?string $clientId = null,
        ?string $redirectURI = null,
        string $host = '',
        ?string $secret = null,
        ?callable $loginSync = null,
        ?callable $logoutSync = null,
        array $cookies = [
            'authName' =>  'hellocoop_auth',
            'oidcName' => 'hellocoop_oidc',
        ],
        bool $production = true,
        string $helloDomain = 'hello.coop',
        ?string $helloWallet = null,
        array $scope = ['openid', 'name', 'email', 'picture'],
        array $providerHint = ['github'],
        array $routes = [
            'loggedIn' => '/', // after callback where we need to take the user.
            'loggedOut' => '/',
            'error' => '/error',
        ],
        ?bool $cookieToken = null, // include encrypted cookie in auth response
        ?bool $logDebug = null,
        ?array $error = null
    ) {
        $this->apiRoute = $apiRoute;
        $this->authApiRoute = $authApiRoute;
        $this->loginApiRoute = $loginApiRoute;
        $this->logoutApiRoute = $logoutApiRoute;
        $this->sameSiteStrict = $sameSiteStrict;
        $this->clientId = $clientId;
        $this->redirectURI = $redirectURI;
        $this->host = $host;
        $this->secret = $secret;
        $this->loginSync = $loginSync;
        $this->logoutSync = $logoutSync;
        $this->cookies = $cookies;
        $this->production = $production;
        $this->helloDomain = $helloDomain;
        $this->helloWallet = $helloWallet;
        $this->scope = $scope;
        $this->providerHint = $providerHint;
        $this->routes = $routes;
        $this->cookieToken = $cookieToken;
        $this->logDebug = $logDebug;
        $this->error = $error;
    }

    public function getProduction(): bool
    {
        return $this->production;
    }

    public function getSameSiteStrict(): ?bool
    {
        return $this->sameSiteStrict;
    }

    public function getError(): ?array
    {
        return $this->error;
    }

    public function getScope(): ?array
    {
        return $this->scope;
    }

    public function getProviderHint(): ?array
    {
        return $this->providerHint;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getLoginSync(): ?callable
    {
        return $this->loginSync;
    }

    public function getLogoutSync(): ?callable
    {
        return $this->logoutSync;
    }

    public function getCookieToken(): ?bool
    {
        return $this->cookieToken;
    }

    public function getApiRoute(): string
    {
        return $this->apiRoute;
    }

    public function getAuthApiRoute(): string
    {
        return $this->authApiRoute;
    }

    public function getLoginApiRoute(): string
    {
        return $this->loginApiRoute;
    }

    public function getLogoutApiRoute(): string
    {
        return $this->logoutApiRoute;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getRedirectURI(): ?string
    {
        return $this->redirectURI;
    }

    public function getHelloDomain(): string
    {
        return $this->helloDomain;
    }

    public function getHelloWallet(): ?string
    {
        return $this->helloWallet;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function getLogDebug(): ?bool
    {
        return $this->logDebug;
    }
}
