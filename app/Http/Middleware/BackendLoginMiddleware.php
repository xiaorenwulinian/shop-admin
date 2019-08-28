<?php

namespace App\Http\Middleware;

use Closure;

class BackendLoginMiddleware
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
        $admin_user_id = session('admin_user_id');
        if (empty($admin_user_id)) {
            return redirect('backend/login');
        }
        return $next($request);
    }
}
