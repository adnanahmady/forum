<?php

namespace App\Custom\Interfaces;

interface Spam
{
    /**
     * Checks if given text is/has spam
     *
     * @param string $text
     * @throws \Exception
     *
     * @return boolean
     */
    public function detect(string $text) : bool ;
}
