<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Product;
use Illuminate\Pagination\Paginator;
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

        // Vercel Serverless: force cookie session & array cache drivers
        // Cookie session stores encrypted session in browser — works across serverless invocations
        if ($this->isVercel()) {
            config(['session.driver' => 'cookie']);
            config(['cache.default' => 'array']);
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
    }
}
