<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'ok' => false,
                'message' => 'No autenticado.',
            ], 401);
        }

        $hasRole = $user
            ->roles()
            ->whereIn('name', $roles)
            ->exists();

        if (!$hasRole) {
            return response()->json([
                'ok' => false,
                'message' => 'No tiene permisos para esta accion.',
            ], 403);
        }

        return $next($request);
    }
}
