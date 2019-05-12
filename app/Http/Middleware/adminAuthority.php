<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class adminAuthority
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
        $author_level = Auth::guard('admin')->user()->level;
        if($author_level > 1):
            return $next($request);
        endif;
        
        abort(403,'Unauthorized');
    }
}
