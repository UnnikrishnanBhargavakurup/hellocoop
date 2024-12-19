<?php

namespace HelloCoop\Exception;

use Throwable;
use Exception;

class SameSiteCallbackException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = "Same Site Callback Exception",
        $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
