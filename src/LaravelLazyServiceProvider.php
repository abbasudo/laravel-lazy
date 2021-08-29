<?php

namespace llabbasmkhll\laravelLazy;

use Illuminate\Support\ServiceProvider;

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
                    InstallBlogPackage::class,
                ]
            );
        }
    }
}
