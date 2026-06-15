<?php

namespace App\Providers;

use App\Support\AdminSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forcer HTTPS en production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Blade::if('adminSession', fn () => AdminSession::active());

        // Enregistrer les policies
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Post::class, \App\Policies\PostPolicy::class);
    }
}
