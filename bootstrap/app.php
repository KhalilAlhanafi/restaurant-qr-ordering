<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'identify.table' => \App\Http\Middleware\IdentifyTable::class,
            'set.locale' => \App\Http\Middleware\SetLocale::class,
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'customer.restrict' => \App\Http\Middleware\CustomerRestriction::class,
        ]);
        
        // Apply customer restriction globally to all web routes except admin
        $middleware->web(append: [
            \App\Http\Middleware\CustomerRestriction::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
