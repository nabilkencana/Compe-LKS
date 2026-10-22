<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next , string ...$roles): Response
    {
        if (!in_array($request->user()->role,$roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden Access',
            ],403);
        };

        return $next($request);
    }
}
