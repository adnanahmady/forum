<?php

namespace App\Custom\Keys;

use App\Custom\Interfaces\Key;

class Thread implements Key
{
    /**
     * @var \App\Thread $thread
     */
    private $thread;

    /**
     * Thread constructor.
     *
     * @param $thread
     */
    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    /**
     * returns thread key
     *
     * @return string
     */
    public function get(): string
    {
        return "threads.{$this->thread->id}.visit";
    }
}
