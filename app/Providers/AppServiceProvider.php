<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Services\Contracts\VideoManagerServiceInterface::class, \App\Services\FFMpegService::class);
        $this->app->bind(\App\Services\Contracts\VideoStorageServiceInterface::class, \App\Services\S3Service::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Video::observe(\App\Observers\VideoObserver::class);
    }
}
