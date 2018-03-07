<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfIsAdmin
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
        $user_type = session('user_type');
        if($user_type == 'administrador')
            return $next($request);
        else
            return redirect('/');
    }
}
