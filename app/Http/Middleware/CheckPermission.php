<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // 1. Ensure user is logged in
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // 2. Check if user's role has the required permission string passed from the route
        if (!$request->user()->hasPermission($permission)) {
            abort(403, 'Unauthorized action. You do not have permission to access this page.');
        }

        return $next($request);
    }
}
