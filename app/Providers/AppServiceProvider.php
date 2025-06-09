<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\FFMpegService;
use App\Services\Contracts\VideoManagerServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(VideoManagerServiceInterface::class, FFMpegService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
