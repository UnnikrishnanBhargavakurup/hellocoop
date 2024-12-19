<?php

namespace HelloCoop\HelloRequest;

interface HelloRequestInterface
{
    /**
     * Fetch a parameter by key from either GET or POST data.
     *
     * @param string $key The key of the parameter to fetch.
     * @param string|null $default Default value if the key is not found.
     * @return string|null The value of the parameter or default.
     */
    public function fetch(string $key, string $default = null): ?string;

    /**
     * Fetch multiple parameters by keys from either GET or POST data.
     *
     * @param array<string> $keys The keys of the parameters to fetch.
     * @return array<string, string> An associative array of parameters.
     */
    public function fetchMultiple(array $keys): array;

    /**
     * Fetch a header by key from the request headers.
     *
     * @param string $key The key of the header to fetch.
     * @param string|null $default Default value if the key is not found.
     * @return string|null The value of the header or default.
     */
    public function fetchHeader(string $key, string $default = null): ?string;

    /**
     * Fetch a cookie value by name.
     *
     * @param string $name The name of the cookie to retrieve.
     * @return string|null The value of the cookie if found, or null if it doesn't exist.
     */
    public function getCookie(string $name): ?string;

    /**
     * Retrieves the current request URI.
     *
     * This method returns the URI of the current HTTP request, which typically includes
     * the path and query string (if present). It can be used to determine the resource
     * or endpoint being accessed.
     *
     * @return string The current request URI.
     */
    public function getRequestUri(): string;

    /**
     * Retrieves the HTTP method of the current request.
     *
     * @return string The HTTP method (e.g., 'GET', 'POST', 'PUT', 'DELETE').
     */
    public function getMethod(): string;
}
