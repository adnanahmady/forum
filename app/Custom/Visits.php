<?php

namespace App\Custom;

use App\Custom\Interfaces\Key;
use App\Custom\Interfaces\Visit;
use Illuminate\Support\Facades\Redis;

/**
 * Class Visits
 *
 * @package App\Custom
 */
class Visits implements Visit
{
    /**
     * visit core cache key
     *
     * @var string $key
     */
    protected $key;

    /**
     * Visits constructor.
     *
     * @param Key $key
     */
    public function __construct(Key $key)
    {
        $this->key = $key->get();
    }

    /**
     * increments visits count
     */
    public function record()
    {
        Redis::incr($this->cacheKey());
    }

    /**
     * deletes all visit records
     */
    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    /**
     * returns count of visit records
     *
     * @return int
     */
    public function count(): int
    {
        return (int) Redis::get($this->cacheKey()) ?? 0;
    }

    /**
     * returns specified number of visit records in reverse order
     *
     * @param int $count
     *
     * @return array
     */
    public function get(int $count = 0): array
    {
        return array_map(
            'json_decode',
            Redis::zrevrange($this->cachekey(), 0, ($count - 1))
        );
    }

    /**
     * return visit caching key
     *
     * @return string
     */
    protected function cacheKey()
    {
        return (app()->environment('testing') ? 'test.' : '').$this->key;
    }

    /**
     * increase sorted set giving key related to visit key
     *
     * @param $key
     */
    public function push($key)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode($key));
    }
}
