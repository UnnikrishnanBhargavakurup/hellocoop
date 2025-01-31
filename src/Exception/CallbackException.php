<?php

namespace HelloCoop\Exception;

use Throwable;
use Exception;

class CallbackException extends Exception
{
    /** @var array<string, mixed> */
    private array $errorDetails;

    /**
     * @param array<string, mixed> $errorDetails
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        array $errorDetails,
        $message = "Callback Exception",
        $code = 0,
        ?Throwable $previous = null
    ) {
        $this->errorDetails = $errorDetails;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<string, mixed>
     */
    public function getErrorDetails(): array
    {
        return $this->errorDetails;
    }
}
