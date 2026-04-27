<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ── Step 2a: Append SystemAuditMiddleware to the API middleware group ──
        // Runs automatically on every API request that is non-GET.
        $middleware->appendToGroup('api', [
            \App\Modules\LoggingAudit\Middlewares\SystemAuditMiddleware::class,
        ]);

        // ── Step 2b: Register named middleware aliases ──
        // Allows controllers/routes to use short names instead of full class paths.
        $middleware->alias([
            'role'          => \App\Modules\AuthIdentity\Middlewares\CheckRoleMiddleware::class,
            'driver.active' => \App\Modules\AuthIdentity\Middlewares\CheckDriverActiveMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

