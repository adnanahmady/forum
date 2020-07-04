<?php

namespace App\Custom\Filters;

use App\Custom\Exceptions\SpamException;
use App\Custom\Interfaces\Filter;

class InvalidPatterns implements Filter
{
    protected $invalidPatterns = [
        '[<>\/]'
    ];

    public function detect(string $text): void
    {
        foreach ($this->invalidPatterns as $pattern)
        {
            if (preg_match("/$pattern/", $text))
            {
                throw new SpamException(__('your reply has an spam hidden in pattern with it'));
            }
        }
    }
}
