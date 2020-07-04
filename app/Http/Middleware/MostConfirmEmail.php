<?php

namespace App\Http\Middleware;

use Closure;

class MostConfirmEmail
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
        if (auth()->check() && ! auth()->user()->confirm) {
            return redirect('/threads')
                ->with(
                    'flash',
                    __('please confirm your email to unleash this ability')
                )
                ->with('level', 'danger');
        }

        return $next($request);
    }
}
