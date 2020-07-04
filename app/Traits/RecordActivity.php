<?php

namespace App\Traits;

use App\Activity;

trait RecordActivity
{
    protected static function bootRecordActivity()
    {
        if (auth()->guest()) return false;

        foreach(static::getRecordActivityEvents() as $event)
        {
            static::$event(function($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function($model) {
            $model->activities->each->delete();
        });
    }

    protected static function getRecordActivityEvents()
    {
        return ['created'];
    }

    public function recordActivity($event)
    {
        $this->activities()
            ->create(
                [
                    'user_id' => auth()->id(),
                    'type' => $this->activityType($event)
                ]
            )
        ;
    }

    protected function activityType($event): string
    {
        return implode('_', [$event, strtolower((new \ReflectionClass($this))->getShortName())]);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
