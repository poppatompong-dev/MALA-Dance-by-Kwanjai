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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
