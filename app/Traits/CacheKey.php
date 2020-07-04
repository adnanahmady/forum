<?php

namespace App\Traits;

trait CacheKey {
    protected $key = 'global';

    protected function key()
    {
        return implode('.', array_map(function ($key) {
            return strtoupper($key);
        }, array_merge([$this->key], func_get_args())));
    }
}
