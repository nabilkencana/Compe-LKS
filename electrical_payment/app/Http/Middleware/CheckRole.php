<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!$request->user()) {
            return response()->json([
                'status' => 'auth_failed',
                'message' => 'Invalid token Or expired'
            ],401);
        }

        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status' => 'forbidden',
                ],403);
        }

        return $next($request);
    }
}
