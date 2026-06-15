<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.blocked' => \App\Http\Middleware\CheckBlocked::class,
            'sync.export' => \App\Http\Middleware\SyncExportMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            return $request->is('admin', 'admin/*')
                ? route('admin.login')
                : route('login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            return $request->is('admin/login')
                ? route('admin.dashboard')
                : route('posts.index');
        });

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CheckBlocked::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
