<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfIsAdmin
{
    public function handle($request, Closure $next)
    {
        $user_type = session('user_type');
        if($user_type == 'admin')
            return $next($request);
        else
            return redirect('/');
    }
}
