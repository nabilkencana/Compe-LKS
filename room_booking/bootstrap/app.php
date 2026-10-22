<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidatonException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(fn()=>null);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
        $exceptions->render(function (AuthenticationException $e , $request) {
            if ($request->is('api/*')) {
                return response()->json([
                'status' => 'auth_fail',
                'message' => 'Invalid or expired token.',
                ],401);
            }
        });
        $exceptions->render(function (ValidatonException $e , $request) {
            if ($request->is('api/*')) {
                return response()->json([
                'status' => 'error',
                'message' => 'Invalid field(s) in request',
                'errors' => $e->errors()
                ],422);
            }
        });
        $exceptions->render(function (NotFoundHttpException $e , $request) {
            if ($request->is('api/*')) {
                return response()->json([
                'status' => 'not_found',
                'message' => 'Resource not found.',
                ],404);
            }
        });
    })->create();
