<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = Auth::user();
        $allowedRoles = explode('|', $roles);

        if (! $user || ! in_array($user->role, $allowedRoles, true)) {
            abort(403, 'Acesso negado.');
        }

        return $next($request);
    }
}
