<?php

namespace App;

use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use RecordActivity;

    protected $fillable = ['user_id', 'favoriteable_id', 'favoriteable_type'];

    public function favoriteable()
    {
        return $this->morphTo();
    }

    public function favorited()
    {
        return strtolower((new \ReflectionClass($this->favoriteable))->getShortName());
    }
}
