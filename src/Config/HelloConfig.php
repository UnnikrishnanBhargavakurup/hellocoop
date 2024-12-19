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
    private ?bool $cookieToken = null;
    private ?string $redirectURI;
    private string $helloDomain;
    private ?string $helloWallet = null;
    private string $host;
    private ?string $secret = null;
    private ?bool $logDebug = null;
    /** @var array<string, int|string>|null */
    private ?array $error = null;
    /** @var array<string> */
    private array $scope;
    /** @var array<string> */
    private array $providerHint;
    /** @var array<string, string> */
    private array $routes;
    /** @var array<string, string> */
    private array $cookies;
    /** @var callable|null */
    private $loginSync;
    /** @var callable|null */
    private $logoutSync;
    private bool $production;

    /**
     * @param string $apiRoute
     * @param string $authApiRoute
     * @param string $loginApiRoute
     * @param string $logoutApiRoute
     * @param bool $sameSiteStrict
     * @param string|null $clientId
     * @param string|null $redirectURI
     * @param string $host
     * @param string|null $secret
     * @param callable|null $loginSync
     * @param callable|null $logoutSync
     * @param array<string, string> $cookies
     * @param bool $production
     * @param string $helloDomain
     * @param string|null $helloWallet
     * @param array<string> $scope
     * @param array<string> $providerHint
     * @param array<string, string> $routes
     * @param bool|null $cookieToken
     * @param bool|null $logDebug
     * @param array<string, int|string>|null $error
     */
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
        ?bool $cookieToken = null,
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

    /**
     * @return array<string, int|string>|null
     */
    public function getError(): ?array
    {
        return $this->error;
    }

    /**
     * @return array<string>|null
     */
    public function getScope(): ?array
    {
        return $this->scope;
    }

    /**
     * @return array<string>|null
     */
    public function getProviderHint(): ?array
    {
        return $this->providerHint;
    }

    /**
     * @return array<string, string>
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @return array<string, string>
     */
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
