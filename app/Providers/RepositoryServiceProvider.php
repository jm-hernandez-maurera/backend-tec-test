<?php

namespace App\Providers;

use App\Interfaces\SecurityPriceRepositoryInterface;
use App\Interfaces\SecurityRepositoryInterface;
use App\Interfaces\SecurityTypeRepositoryInterface;
use App\Repositories\SecurityPriceRepository;
use App\Repositories\SecurityRepository;
use App\Repositories\SecurityTypeRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SecurityPriceRepositoryInterface::class, SecurityPriceRepository::class);
        $this->app->bind(SecurityRepositoryInterface::class, SecurityRepository::class);
        $this->app->bind(SecurityTypeRepositoryInterface::class, SecurityTypeRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
