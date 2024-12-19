<?php

namespace HelloCoop\Type\Common;

trait OptionalOrgClaimTrait
{
    /**
     * @var array{id: string, domain: string}|null
     * Optional organization claim.
     */
    public ?array $org;
}
