<?php

namespace App\Custom\Keys;

use App\Custom\Interfaces\Key;

class User implements Key
{
    protected $key;
    protected $user;

    public function __construct($user, string $key)
    {
        $this->key = $key;
        $this->user = $user;
    }

    public function get(): string
    {
        return "users.{$this->user->id}.{$this->key}";
    }
}
