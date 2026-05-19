<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')->group(function () {
                require __DIR__.'/../routes/admin.php';
                require __DIR__.'/../routes/pegawai.php';
                require __DIR__.'/../routes/kph.php';
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withProviders([
        App\Providers\LogServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
