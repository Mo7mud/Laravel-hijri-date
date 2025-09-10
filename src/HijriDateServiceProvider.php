<?php

namespace Mo7mud\HijriDate;

use Illuminate\Support\ServiceProvider;

class HijriDateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('HijriDate', \Mo7mud\HijriDate\HijriDate::class);
        
        $this->mergeConfigFrom(__DIR__ . '/../config/hijri.php', 'hijri');

        require_once __DIR__ . '/helpers.php';
    }

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'hijri');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/hijri.php' => config_path('hijri.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../lang' => $this->app->langPath('vendor/hijri'),
            ], 'lang');

            $this->commands([
                Console\FetchG2HMap::class,
            ]);
        }
    }
}
