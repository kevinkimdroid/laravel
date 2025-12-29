<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();

        if (! $user) {
            // Not logged in: send to login
            return redirect()->route('login');
        }

        if (! method_exists($user, 'hasRole') || ! $user->hasRole($role)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
