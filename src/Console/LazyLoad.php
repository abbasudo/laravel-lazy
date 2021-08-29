<?php

namespace Llabbasmkhll\LaravelLazy\Console;

use Illuminate\Console\Command;
use Llabbasmkhll\LaravelLazy\Paths\FilePath;
use Llabbasmkhll\LaravelLazy\Paths\LaravelPaths;

class LazyLoad extends Command
{
    protected $signature = 'lazy:load';

    protected $description = 'auto create and fill models based on migrations';

    public function handle()
    {
//        explode("\n", file_get_contents(FilePath::getAbsFilePaths(LaravelPaths::migrationDirs())[0]));
    }
}
