<?php

namespace App\Custom\Interfaces;

interface Key
{
    /**
     * returns rendered key
     *
     * @return string
     */
    public function get(): string ;
}
