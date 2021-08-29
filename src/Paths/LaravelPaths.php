<?php

namespace Llabbasmkhll\LaravelLazy\Paths;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class LaravelPaths
{
    public static function migrationDirs()
    {
        // normalize the migration paths
        $migrationDirs = [];

        foreach (app('migrator')->paths() as $path) {
            // Excludes the migrations within "vendor" folder:
            if (! Str::startsWith($path, [base_path('vendor')])) {
                $migrationDirs[] = FilePath::normalize($path);
            }
        }

        $migrationDirs[] = app()->databasePath().DIRECTORY_SEPARATOR.'migrations';

        return $migrationDirs;
    }
}
