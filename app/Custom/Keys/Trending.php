<?php

namespace App\Custom\Keys;

use App\Custom\Interfaces\Key;

class Trending implements Key
{
    protected $subject;

    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }

    public function get(): string
    {
        return "trending_" . $this->subject;
    }
}
