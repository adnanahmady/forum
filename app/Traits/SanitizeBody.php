<?php

namespace App\Traits;

trait SanitizeBody
{
    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }
}
