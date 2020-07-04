<?php

namespace App\Custom\Interfaces;

interface Filter
{
    /**
     * Checks if given text is/has spam
     *
     * @param string $text
     * @throws \Exception
     */
    public function detect(string $text): void;
}
