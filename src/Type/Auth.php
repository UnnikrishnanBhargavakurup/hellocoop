<?php

namespace HelloCoop\Type;

use InvalidArgumentException;

class Auth
{
    public bool $isLoggedIn;
    public ?string $cookieToken;
    public ?AuthCookie $authCookie;

    public function __construct(bool $isLoggedIn, ?AuthCookie $authCookie = null, ?string $cookieToken = null)
    {
        $this->isLoggedIn = $isLoggedIn;
        $this->authCookie = $authCookie;
        $this->cookieToken = $cookieToken;
    }

    /** Convert the instance to an array of key-value pairs.
     * @return array<string, bool|string|array<string, mixed>|null>
     */
    public function toArray(): array
    {
        return [
            'isLoggedIn' => $this->isLoggedIn,
            'cookieToken' => $this->cookieToken,
            'authCookie' => $this->authCookie ? $this->authCookie->toArray() : null,
        ];
    }

    /** Create an instance from an array of key-value pairs.
     * @param array<string, bool|string|array<string>|null>|null $data
     * @return Auth
     */
    public static function fromArray(?array $data): self
    {
        // Check for required fields in the array
        if (!isset($data['isLoggedIn'])) {
            throw new InvalidArgumentException('Missing required field "isLoggedIn".');
        }

        // Create the AuthCookie instance from the array if it exists
        $authCookie = isset($data['authCookie']) && is_array($data['authCookie'])
            ? AuthCookie::fromArray($data['authCookie'])
            : null;

        $cookieToken = isset($data['cookieToken']) && is_string($data['cookieToken'])
            ? $data['cookieToken']
            : null;

        // Return the new Auth instance
        return new self(
            is_bool($data['isLoggedIn']) ? $data['isLoggedIn'] : false,
            $authCookie,
            $cookieToken
        );
    }
}

/**
 * $authCookie = new AuthCookie('user123', time());
 * $authCookie->setExtraProperty('role', 'admin');
 * $auth = new Auth(true, $authCookie, 'token123');

 * echo "User is logged in: " . ($auth->isLoggedIn ? 'Yes' : 'No') . PHP_EOL;
 * echo "User role: " . $auth->authCookie->getExtraProperty('role') . PHP_EOL;
 */
