<?php

namespace Llabbasmkhll\LaravelLazy;

use Dotenv\Store\File\Paths;
use Faker\Core\File;
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
    }
}
