<?php

namespace App\Providers\Adapter;

use App\Adapter\Framework\CollectionFactory;
use Core\Port\Framework\CollectionFactoryInterface;
use Illuminate\Support\ServiceProvider;

class FrameworkServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CollectionFactoryInterface::class, CollectionFactory::class);
    }
}
