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
        if ($request->user()->role !== 'staff') {
            return response()->json([
            'message' => 'Forbidden Access!',
            ],403);
        }

        return $next($request);
    }
}
