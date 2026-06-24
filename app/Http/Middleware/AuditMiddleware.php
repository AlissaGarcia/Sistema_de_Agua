<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $route = $request->route()?->getName() ?? $request->path();

        DB::table('audit_logs')->insert([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'route_name' => $route,
            'http_method' => $request->method(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_payload' => json_encode($this->sanitizeRequest($request)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $next($request);
    }

    protected function sanitizeRequest(Request $request): array
    {
        $payload = $request->except(['password', 'password_confirmation', '_token', '_method']);

        return array_filter($payload, fn ($value) => ! is_null($value));
    }
}
