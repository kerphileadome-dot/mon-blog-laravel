<?php

namespace App\Providers;

use App\Models\Post;
use App\Policies\PostPolicy;
use App\Support\AdminSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
            URL::forceScheme('https');
        }

        Blade::if('adminSession', fn () => AdminSession::active());

        // Enregistrer les policies
        Gate::policy(Post::class, PostPolicy::class);
    }
}
