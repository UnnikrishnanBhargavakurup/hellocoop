<?php

namespace HelloCoop\Renderers;

interface PageRendererInterface
{
    /**
     * Render an error page.
     */
    public function renderErrorPage(
        string $error,
        string $errorDescription,
        string $errorURI,
        string $targetURI = '/'
    ): string;

    /**
     * Render a same-site page.
     *
     * @return string Rendered page content.
     */
    public function renderSameSitePage(): string;

    /**
     * Render redirect URI bounce page.
     *
     * @return string Rendered page content.
     */
    public function renderRedirectURIBounce(): string;

    /**
     * Render wild card console.
     *
     * @return string Rendered page content.
     */
    public function renderWildcardConsole(string $uri, string $targetURI, string $appName, string $redirectURI): string;
}
