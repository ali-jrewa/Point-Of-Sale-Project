<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Ensure the user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // 2. Ensure the user has a role assigned
        if (!$request->user()->role) {
            abort(403, 'You have no assigned role in this system.');
        }

        // 3. Check if the user's role name matches any of the allowed roles passed to the middleware
        if (!in_array($request->user()->role->name, $roles)) {
            abort(403, 'Unauthorized. This area is restricted to: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}
