<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MusicServiceInterface;
use App\Services\MusicService;

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
        //
    }
}
