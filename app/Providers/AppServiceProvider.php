<?php

namespace App\Providers;

use App\Channel;
use App\Custom\Keys\Trending;
use App\Custom\Keys\User;
use App\Custom\Visits;
use App\Traits\CacheKey;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use CacheKey;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal())
        {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->alias(\Barryvdh\Debugbar\Facade::class, 'Debugbar');
        }
        $this->app->singleton('UserVisit', function () {
            return new Visits(new User(auth()->user(), 'visited_threads'));
        });

        $this->app->singleton('TrendingThreads', function () {
            return new Visits(new Trending('threads'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $channels = Cache::rememberForever($this->key('global', 'channels'), function () {
                return Channel::all();
            });

            $view->with('channels', $channels);
        });

//        Gate::before(function ($user) {
//            if ($user->name == 'adnan')
//            {
//                return true;
//            }
//        });

        Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
    }
}
