<?php

namespace HelloCoop\Exception;

use BadMethodCallException;

class NotImplementedException extends BadMethodCallException
{
    /** @var string  */
    protected $message = 'Not Implemented.';
}
