<?php

namespace HelloCoop\Type\Common;

trait OptionalAccountClaimsTrait
{
    /**
     * @var array<string, array{id: string, username: string}>
     * An associative array of account claims (e.g., ['github' => ['id' => '123', 'username' => 'user']]).
     */
    public array $claims = [];
}
