<?php

namespace App\Http\Middleware;

use Closure;

class JustAdminstrators
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        return abort(403, 'You dont have permission to perform this action');
    }
}
