<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    private $role;

    public function __construct($role = null)
    {
        $this->role = $role;
    }

    public function handle($request, Closure $next, $role)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
            // redirect to login page if role doesn’t match
            header('Location: /login');
            exit;
        }
        return $next($request);
    }
}
