<?php

namespace App\Custom\Exceptions;

use Throwable;

class SpamException extends \Exception
{
    public function __construct(string $message = "", int $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
