<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Product;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Vercel Cache Workaround
        if (!config()->has('cache.stores.array')) {
            config(['cache.stores.array' => [
                'driver' => 'array',
                'serialize' => false,
            ]]);
        }
        if (!config()->has('permission.cache.store')) {
            config(['permission.cache.store' => 'default']);
        }

        // Vercel Serverless: use database for cache (persists across invocations)
        // and cookie for session (no server-side storage needed)
        if ($this->isVercel()) {
            config(['cache.default' => 'database']);
            config(['cache.stores.database' => [
                'driver'     => 'database',
                'table'      => 'cache',
                'connection' => null,
                'lock_connection' => null,
            ]]);
            config(['session.driver' => 'cookie']);
        }
    }

    /**
     * Detect if the application is running on Vercel.
     */
    protected function isVercel(): bool
    {
        return isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])
            || str_contains(env('APP_URL', ''), 'vercel.app');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Force HTTPS on Vercel to fix Mixed Content errors
        // Vercel always serves over HTTPS and sets X-Forwarded-Proto header
        if ($this->isVercel()) {
            URL::forceScheme('https');
        }
    }
}
