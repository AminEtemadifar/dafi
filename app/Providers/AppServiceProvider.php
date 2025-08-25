<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MusicServiceInterface;
use App\Services\MusicService;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MusicServiceInterface::class, MusicService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure pagination renders with our custom vendor views (not Tailwind/Bootstrap)
        Paginator::defaultView('vendor.pagination.default');
        Paginator::defaultSimpleView('vendor.pagination.simple-default');
    }
}
