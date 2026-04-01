<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * This middleware keeps access simple by allowing only users with
     * the requested role (buyer or seller).
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (!$user || $user->role !== $role) {
            abort(403, 'You are not allowed to access this page.');
        }

        return $next($request);
    }
}
