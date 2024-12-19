<?php

namespace HelloCoop\Exception;

use Exception;

class CryptoFailedException extends Exception
{
    /** @var string  */
    protected $message = 'Crypto failed. There was an error encrypting the data.';
}
