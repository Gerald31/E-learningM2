<?php

namespace App\Providers\Adapter;

use App\Adapter\Helper\Datetime;
use App\Adapter\Helper\StorageHelper;
use Core\Port\Helper\DatetimeInterface;
use Core\Port\Helper\StorageInterface;
use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DatetimeInterface::class, Datetime::class);
        $this->app->singleton(StorageInterface::class, StorageHelper::class);
    }
}
