<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class staff
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role === 'staff') {
            return $next($request);
            }
            return response()->json([
            'message' => 'You are not authorized to access staff orders.',
            ],403);
            
            
    }
}
