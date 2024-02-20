<?php

namespace Countrycodevendor\Countrycode;

use Illuminate\Support\ServiceProvider;

class countryCodeServiceProvider extends ServiceProvider
{
    public function boot()
    {
          $this->loadRoutesFrom(__DIR__.'/routes/web.php');
          $this->loadViewsFrom(__DIR__.'/views', 'countrycode');
          $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    public function register()
    {

    }
}
