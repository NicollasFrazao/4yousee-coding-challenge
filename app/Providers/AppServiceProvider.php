<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\FFMpegService;
use App\Services\Contracts\VideoManagerServiceInterface;

use App\Services\S3Service;
use App\Services\Contracts\VideoStorageServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(VideoManagerServiceInterface::class, FFMpegService::class);
        $this->app->bind(VideoStorageServiceInterface::class, S3Service::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
