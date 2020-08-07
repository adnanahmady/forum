<?php

namespace App;

use App\Constants\Reputation;
use App\Custom\Keys\Thread as ThreadKey;
use App\Custom\Visits;
use App\Events\ThreadHasNewReply;
use App\Traits\RecordActivity;
use App\Traits\SanitizeBody;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use RecordActivity, Searchable, SanitizeBody;

    protected $fillable = [
        'title',
        'body',
        'locked',
        'channel_id',
        'slug',
        'best_reply_id'
    ];
    protected $appends = ['isSubscribed'];
    private $endPoint = '/threads';

    public function toSearchableArray()
    {
        $this->channel;

        return $this->toArray() + ['path' => $this->path()];
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('creator', function ($builder) {
            $builder->with('creator');
        });

        static::deleting(function ($model) {
            $model->replies->each->delete();
        });

        static::creating(function ($model) {
            $model->slug = $model->title;

            Reputation::award($model->creator, Reputation::THREAD_WAS_PUBLISHED);
        });
    }

    public function subscribe($userId = null)
    {
        $this->subscribes()->create(['user_id' => $userId ?: auth()->id()]);

        return $this;
    }

    public function subscribes()
    {
        return $this->hasMany(Subscribe::class);
    }

    public function setSlugAttribute($value)
    {
        if ($this->whereSlug($slug = str_slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug)
    {
        $slugs = $this->whereTitle($this->title)->latest('id')->limit(3)->pluck('slug');
        $max = explode(
            '-',
            count($slugs) > 1 ? $slugs->first() : 1
        );

        if (is_numeric($num = array_pop($max))) {
            return "{$slug}-" . ((int)$num + 1);
        }

        return "{$slug}-" . 2;
    }

    public function getIsSubscribedAttribute()
    {
        return !!$this->subscribes->where('user_id', auth()->id())->count();
    }

    public function unsubscribe($userId = null)
    {
        return $this->subscribes()->where(['user_id' => $userId ?: auth()->id()])->delete();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function path()
    {
        return implode(
            '/',
            array_merge(
                [$this->endPoint, $this->channel->slug, $this->slug],
                func_get_args()
            )
        );
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadHasNewReply($this, $reply));

        return $reply;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function hasNewUpdate()
    {
        if (auth()->guest()) return false;

        $key = auth()->user()->visitThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function visits()
    {
        return new Visits(new ThreadKey($this));
    }

    public function bestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => (int)$reply->id]);

        Reputation::award($reply->owner, Reputation::BEST_REPLY_AWARDED);
    }

    public function setLock($lock = true)
    {
        $this->update(['locked' => $lock ? now() : null]);
    }

    public function unlock()
    {
        $this->setLock(false);
    }
}
