<?php

namespace Countrycodevendor\Countrycode;

use Illuminate\Support\ServiceProvider;

class countryCodeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'countrycode');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'countrycode');

        $this->publishes([
            __DIR__ . '/public' => public_path('vendor/countrycode'),
            __DIR__ . '/public/data' => public_path('vendor/countrycode/data'),
            __DIR__ . '/public/flags' => public_path('vendor/countrycode/flags'),
            __DIR__ . '/public/data' => public_path('vendor/countrycode/data'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/lang' => resource_path('lang/vendor/countrycode'),
        ], 'countrycode-lang');

        $this->publishes([
            __DIR__ . '/views' => resource_path('views/countrycode'),
        ], 'countrycode-views');

    }

    public function register()
    {

    }
}
