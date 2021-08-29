<?php

namespace Llabbasmkhll\LaravelLazy\Paths;

use Illuminate\Support\Str;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

class FilePath
{
    /*
     * Normalize file path to standard formal
     * For a path like: "/usr/laravel/app\Http\..\..\database" returns "/usr/laravel/database".
     */
    public static function normalize($path)
    {
        $dir = \str_replace(['\\', '/', '//', '\\\\'], DIRECTORY_SEPARATOR, $path);

        $sections = \explode(DIRECTORY_SEPARATOR, $dir);

        $result = [];
        foreach ($sections as $section) {
            if ($section == '..') {
                \array_pop($result);
            } else {
                $result[] = $section;
            }
        }

        return \implode(DIRECTORY_SEPARATOR, $result);
    }

    /*
     * get all ".php" files in directory by giving a path.
     */
    public static function getAbsFilePaths($dirs)
    {
        if (! $dirs) {
            return [];
        }

        try {
            $files = Finder::create()->files()->name('*.php')->in($dirs);

            $paths = [];
            foreach ($files as $f) {
                $paths[] = $f->getRealPath();
            }

            return $paths;
        } catch (DirectoryNotFoundException $e) {
            return [];
        }
    }
}
