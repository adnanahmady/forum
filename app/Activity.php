<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function subject()
    {
        return $this->morphTo();
    }

    public function action()
    {
        return __(str_replace('_', ' ', $this->type));
    }

    /**
     * get latest 30 activities with their subjects
     *
     * @param $user
     * @param $limit
     *
     * @return mixed
     */
    public static function feed($user, $limit = 30)
    {
        return static::whereUserId($user->id)
            ->latest()
            ->with('subject')
            ->take($limit)
            ->get()
            ->groupBy(
                function ($activity) {
                    return $activity->created_at->format('Y/m/d D');
                }
            );
    }
}
