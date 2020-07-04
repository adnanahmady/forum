<?php

namespace App\Custom\Filters;

use App\Custom\Exceptions\SpamException;
use App\Custom\Interfaces\Filter;

class InvalidRepeatedLetters implements Filter
{
    protected $pattern = '(.)\\1{4,}';

    public function detect(string $text): void
    {
        if (preg_match("/{$this->pattern}/", $text))
        {
            throw new SpamException(__('your reply has unusual repeated letters'));
        }
    }
}
