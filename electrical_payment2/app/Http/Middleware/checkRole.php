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
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'without valid token'
            ],401);
        }
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Fobidden Access'
            ],401);
        }

        return $next($request);
    }
}
