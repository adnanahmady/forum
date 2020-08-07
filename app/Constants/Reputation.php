<?php

namespace App\Constants;

use App\User;

/**
 * Class Reputation
 * a single source of truth
 *
 * @package App\Constants
 */
class Reputation
{
    /**
     * Users reputation column.
     */
    const REPUTATION = 'reputation';
    /**
     * Reputation points for publishing a thread.
     */
    const THREAD_WAS_PUBLISHED = 10;
    /**
     * Reputation points for reply a thread.
     */
    const REPLY_POSTED = 2;
    /**
     * Reputation points for the reply be selected as best reply.
     */
    const BEST_REPLY_AWARDED = 50;

    /**
     * Reputation points for the reply be selected as favorite.
     */
    const REPLY_MARKED_AS_FAVORITE = 4;

    /**
     * Increments Users reputation column value.
     *
     * @param User $user
     * @param int $points
     *
     * @return void
     */
    public static function award(User $user, int $points): void
    {
        $user->increment(self::REPUTATION, $points);
    }

    /**
     * Decrements Users reputation column value.
     *
     * @param User $user
     * @param int $points
     *
     * @return void
     */
    public static function reward(User $user, int $points): void
    {
        $user->decrement(self::REPUTATION, $points);
    }
}
