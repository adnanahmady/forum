<?php

namespace App\Custom;

use App\Custom\Filters\InvalidKeywords;
use App\Custom\Filters\InvalidPatterns;
use App\Custom\Filters\InvalidRepeatedLetters;
use App\Custom\Interfaces\Spam as SpamInterface;

class Spam implements SpamInterface
{
    protected $filters = [
        InvalidKeywords::class,
        InvalidRepeatedLetters::class,
    ];

    public function detect(string $text): bool
    {
        foreach ($this->filters as $filter)
        {
            app($filter)->detect($text);
        }

        return false;
    }
}
