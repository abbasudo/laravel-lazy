<?php

namespace Llabbasmkhll\LaravelLazy;

use Illuminate\Support\ServiceProvider;
use Llabbasmkhll\LaravelLazy\Console\LazyLoad;

class LaravelLazyServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole() and app()['env'] !== 'production') {
            $this->commands(
                [
                    LazyLoad::class,
                ]
            );
        }

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__.'/../config/config.php' => config_path('lazy.php'),
                ],
                'lazy'
            );
        }
    }
}
