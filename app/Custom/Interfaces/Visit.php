<?php

namespace App\Custom\Interfaces;

interface Visit
{
    /**
     * resets visits count
     */
    public function reset();

    /**
     * counts records of visit
     *
     * @return int
     */
    public function count(): int;

    /**
     * records visit
     */
    public function record();

    /**
     * returns given count of records
     *
     * @param int $count
     *
     * @return array
     */
    public function get(int $count): array ;
    
    /**
     * records a sorted set
     * 
     * @param $key
     */
    public function push($key);
}
