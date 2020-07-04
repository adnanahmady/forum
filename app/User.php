<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'confirm_token', 'confirm'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function confirm()
    {
        $this->update([
            'confirm' => now(),
            'confirm_token' => null
        ]);
    }

    public function subscribes()
    {
        return $this->hasMany(Subscribe::class);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->latest();
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ?
            Storage::url($avatar) :
            asset('images/avatars/default.png'));
    }

    /**
     * record that the user has read the given thread
     *
     * @param $thread
     *
     * @throws \Exception
     */
    public function read($thread)
    {
        cache()->forever($this->visitThreadCacheKey($thread), Carbon::now());

        resolve('UserVisit')->push([
            'id' => $thread->id,
            'title' => $thread->title,
            'path' => $thread->path()
        ]);
    }

    public function visitThreadCacheKey($thread)
    {
        return sprintf('users.%s.visit.%s', $this->id, $thread->id);
    }

    public function latestReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function isAdmin()
    {
        return in_array($this->name, ['adnan', 'erfan']);
    }
}
