<?php

namespace HelloCoop\Exception;

use Throwable;
use Exception;

class SameSiteCallbackException extends Exception
{
    private array $errorDetails;

    public function __construct(
        string $message = "Same Site Callback Exception",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
