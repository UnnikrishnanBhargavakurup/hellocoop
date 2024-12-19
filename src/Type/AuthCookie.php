<?php

namespace HelloCoop\Type;

use InvalidArgumentException;

// Authentication cookie class, extending Claims
class AuthCookie extends Claims
{
    /** @var int */
    public int $iat;

    /**
     * @var array<string, mixed>
     * Allow arbitrary optional properties.
     */
    public array $extraProperties = [];

    public function __construct(string $sub, int $iat)
    {
        parent::__construct($sub);
        $this->iat = $iat;
    }

    /**
     * Add an extra property.
     * @param string $key
     * @param mixed $value
     */
    public function setExtraProperty(string $key, $value): void
    {
        $this->extraProperties[$key] = $value;
    }

    /**
     * Get an extra property.
     * @return mixed
     */
    public function getExtraProperty(string $key)
    {
        return $this->extraProperties[$key] ?? null;
    }

    /**
     * Create an instance from an array of key-value pairs.
     * @param array<string, int|string> $data
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['sub'], $data['iat'])) {
            throw new InvalidArgumentException('Missing required keys "sub" or "iat".');
        }

        $instance = new self(
            is_string($data['sub']) ? $data['sub'] : "",
            is_int($data['iat']) ? $data['iat'] : 0
        );

        foreach ($data as $key => $value) {
            if (!in_array($key, ['sub', 'iat'], true)) {
                $instance->setExtraProperty($key, $value);
            }
        }

        return $instance;
    }

    /**
     * Convert the instance to an array of key-value pairs.
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge(['sub' => $this->sub, 'iat' => $this->iat], $this->extraProperties);
    }
}
