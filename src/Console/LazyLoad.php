<?php

namespace JohnDoe\BlogPackage\Console;

use Illuminate\Console\Command;

class LazyLoad extends Command
{
    protected $signature = 'lazy:load';

    protected $description = 'auto create and fill model based on migration';

    public function handle()
    {
    }
}
