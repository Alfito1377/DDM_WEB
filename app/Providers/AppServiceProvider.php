<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

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
        // Force HTTPS selain di local
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

        // Gunakan Tailwind untuk pagination
        Paginator::useTailwind();
    }
}

