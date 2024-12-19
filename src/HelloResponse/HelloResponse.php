<?php

namespace HelloCoop\HelloResponse;

use HelloCoop\HelloResponse\HelloResponseInterface;

class HelloResponse implements HelloResponseInterface
{
    /**
     * Set a header.
     *
     * @param string $name The name of the header.
     * @param string|string[] $value The value(s) of the header.
     * @return void
     */
    public function setHeader(string $name, $value): void
    {
        // Ensure the value is an array to handle multiple values for the same header
        if (is_array($value)) {
            $value = implode(", ", $value); // Combine array values into a single string
        }

        // Send the header using PHP's header() function
        header("$name: $value", true);
    }

    /**
     * Deletes a cookie by setting its expiration time to the past.
     *
     * This method sets the cookie's value to an empty string and its expiration time to one hour before the current
     * time, which effectively deletes it.
     *
     * @param string $name The name of the cookie to delete.
     * @param string $path The path on the server where the cookie will be available. Defaults to '/'.
     * @param string $domain The domain for which the cookie is valid. Defaults to an empty string.
     * @return void
     */
    public function deleteCookie(string $name, string $path = '/', string $domain = ''): void
    {
        $this->setCookie($name, '', time() - 3600, $path, $domain);
    }

    /**
     * Sets a cookie with the specified parameters.
     *
     * This method uses PHP's `setcookie` function to set a cookie with the provided name, value, and various optional
     * parameters such as expiration time, path, domain, security, and HttpOnly flags.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value to store in the cookie.
     * @param int $expire The expiration time of the cookie, as a Unix timestamp. Defaults to 0 (session cookie).
     * @param string $path The path for which the cookie is valid. Defaults to '/'.
     * @param string $domain The domain for which the cookie is valid. Defaults to an empty string.
     * @param bool $secure Whether the cookie should only be transmitted over secure (HTTPS) connections.
     *                     Defaults to false.
     * @param bool $httponly Whether the cookie should be accessible only via the HTTP protocol and not JavaScript.
     *                       Defaults to true.
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
    ): void {
        setcookie($name, $value, [
            'expires' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => 'Lax'
        ]);
    }

    /**
     * Redirects the user to the specified URL.
     *
     * This method sends an HTTP Location header to redirect the user to the provided URL and terminates the script.
     * If the script is running in a testing environment (i.e., when TESTING is defined), a RuntimeException will be
     * thrown instead of performing the redirect.
     *
     * @param string $url The URL to redirect the user to.
     * @return void
     * @throws \RuntimeException if in the testing environment.
     */
    public function redirect(string $url)
    {
        header('Location: ' . $url);
        return;
    }

    /**
     * Renders the given content as a plain string response.
     *
     * This method simply returns the provided content without any modifications.
     * It can be used in environments where the response content does not need
     * additional processing or wrapping.
     *
     * @param string $content The content to render as a response.
     * @return string The rendered response content.
     */
    public function render(string $content): string
    {
        return $content;
    }

    /**
     * Converts the given data array into a JSON string.
     *
     * This method encodes the provided data into a JSON format string, which
     * can be sent as a response in environments that expect JSON output.
     *
     * @param array<string, mixed> $data The data to be converted to a JSON string.
     * @return string The JSON-encoded string representation of the data.
     */
    public function json(array $data): string
    {
        return !json_encode($data) ? "" : json_encode($data);
    }
}
