<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            // redirect to login page
            header('Location: /login');
            exit;
        }
        return $next($request);
    }
}
