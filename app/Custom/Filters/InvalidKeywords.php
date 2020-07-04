<?php

namespace App\Custom\Filters;

use App\Custom\Exceptions\SpamException;
use App\Custom\Interfaces\Filter;

class InvalidKeywords implements Filter
{
    protected $invalidKeywords = [
        'invalid'
    ];

    public function detect(string $text): void
    {
        foreach ($this->invalidKeywords as $keyword)
        {
            if (stripos($text, $keyword) !== false)
            {
                throw new SpamException(__('your reply has an spam keyword with it'));
            }
        }
    }
}
