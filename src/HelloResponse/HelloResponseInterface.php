<?php

namespace HelloCoop\HelloResponse;

/**
 * Interface for handling HTTP response headers and cookies.
 */
interface HelloResponseInterface
{
    /**
     * Set a header.
     *
     * @param string $name The name of the header.
     * @param string|string[] $value The value(s) of the header.
     * @return void
     */
    public function setHeader(string $name, $value): void;

    /**
     * Set a cookie.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     * @param int $expire The expiration time of the cookie as a Unix timestamp. Default is 0 (session cookie).
     * @param string $path The path on the server where the cookie will be available. Default is '/'.
     * @param string $domain The domain that the cookie is available to. Default is an empty string.
     * @param bool $secure Indicates if the cookie should only be transmitted over a secure HTTPS connection.
     *                     Default is false.
     * @param bool $httponly When true, makes the cookie accessible only through the HTTP protocol, restricting access
     *                       from JavaScript. Default is true.
     * @return void
     */
    public function setCookie(
        string $name,
        string $value,
        int $expire = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httponly = true
    ): void;

    /**
     * Delete a cookie.
     *
     * @param string $name The name of the cookie to delete.
     * @param string $path The path on the server where the cookie is available. Default is '/'.
     * @param string $domain The domain that the cookie is available to. Default is an empty string.
     * @return void
     */
    public function deleteCookie(string $name, string $path = '/', string $domain = ''): void;

    /**
     * Redirects the user to the specified URL.
     *
     * This method sends an HTTP Location header to redirect the user to the provided URL.
     *
     * @param string $url The URL to redirect the user to.
     * @return mixed
     */
    public function redirect(string $url);

    /**
     * Converts the given data array into a JSON response format.
     *
     * This method prepares the data to be sent as a JSON response. It allows
     * different frameworks or environments to implement their own mechanisms
     * for encoding and returning JSON data.
     *
     * @param array<string, mixed> $data The data to be converted to a JSON response.
     * @return string The string encoded JSON response data.
     */
    public function json(array $data): string;

    /**
     * Renders the given content as an HTTP response.
     *
     * This method is responsible for returning the content in a format that
     * can be sent as a response to the client. The implementation will vary
     * depending on the framework or environment being used.
     *
     * @param string $content The content to render as a response.
     * @return string The rendered response content.
     */
    public function render(string $content): string;
}
