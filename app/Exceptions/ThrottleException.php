<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ThrottleException extends Exception
{
    public function __construct($message = "", $code = 429, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
