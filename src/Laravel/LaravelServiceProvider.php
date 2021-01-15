<?php

namespace SslCorp\Laravel;

use Illuminate\Support\ServiceProvider;
use SslCorp\Api;

class LaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerConfig();
        $this->registerIoc();
    }

    public function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'ssl');
    }

    public function registerIoc()
    {
        $this->app->singleton('sslcorp', function () {
            return new Api();
        });
    }
}
