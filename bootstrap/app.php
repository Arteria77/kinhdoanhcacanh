<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
<<<<<<< HEAD
        $middleware->validateCsrfTokens(except: [
            '/api/calculate-shipping-fee*',
            '/payment/vnpay-return',
            '/api/sepay/webhook',
        ]);
=======
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();