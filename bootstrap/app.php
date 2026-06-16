<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckBlocked;
use App\Http\Middleware\EnsureAdminLoginKey;
use App\Http\Middleware\SyncExportMiddleware;
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
            'admin' => AdminMiddleware::class,
            'admin.login.key' => EnsureAdminLoginKey::class,
            'check.blocked' => CheckBlocked::class,
            'sync.export' => SyncExportMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            return $request->is('admin', 'admin/*')
                ? route('posts.index')
                : route('login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            return $request->is('admin/login')
                ? route('admin.dashboard')
                : route('posts.index');
        });

        $middleware->appendToGroup('web', [
            CheckBlocked::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
