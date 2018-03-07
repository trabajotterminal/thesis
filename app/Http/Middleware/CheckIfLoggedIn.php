<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfLoggedIn
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
        $user = session('user');
        if($user != null)
            return $next($request);
        return redirect('/login');

    }
}
