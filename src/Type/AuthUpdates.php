<?php

namespace HelloCoop\Type;

class AuthUpdates extends Claims implements \ArrayAccess
{
    private array $additionalProperties = [];

    public function __construct(string $sub, array $updates = [])
    {
        parent::__construct($sub);
        $this->additionalProperties = $updates;
    }

    // Magic methods for dynamic properties
    public function __set(string $name, $value): void
    {
        $this->additionalProperties[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->additionalProperties[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->additionalProperties[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->additionalProperties[$name]);
    }

    // ArrayAccess implementation
    public function offsetExists($offset): bool
    {
        return isset($this->additionalProperties[$offset]);
    }

    public function offsetGet($offset): ?string
    {
        return $this->additionalProperties[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->additionalProperties[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->additionalProperties[$offset]);
    }

    public function toArray(): array
    {
        return array_merge(get_object_vars($this), $this->additionalProperties);
    }
}
