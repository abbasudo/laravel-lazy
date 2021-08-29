<?php

namespace Llabbasmkhll\LaravelLazy\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Llabbasmkhll\LaravelLazy\Paths\FilePath;
use Llabbasmkhll\LaravelLazy\Paths\LaravelPaths;

class LazyLoad extends Command
{
    protected $signature = 'lazy:load';

    protected $description = 'auto create and fill models based on migrations';

    public function handle()
    {
        foreach (FilePath::getAbsFilePaths(LaravelPaths::migrationDirs()) as $migration) {
            $tables = $this->separateTables(file_get_contents($migration));
            foreach ($tables as $table) {
                $model   = $this->getTablesName($table);
                $columns = $this->getColumns($table);

                $this->info($model);
                foreach ($columns as $column) {
                    $this->info($column[0].' => '.$column[1]);
                }
            }
        }
    }

    public function separateTables($file): array
    {
        $separated = explode('Schema::create', $file);
        array_shift($separated);

        return $separated;
    }

    public function getTablesName($table): string
    {
        return Str::singular(explode("'", $table)[1]);
    }

    public function getColumns($table): array
    {
        $columns = [];

        foreach (explode("\n", $table) as $line) {
            if (Str::containsAll($line, ['$table->', "'"])) {
                $type = explode('(', explode('$table->', $line)[1])[0];
                $name = explode("'", $line)[1];
                array_push($columns, [$type, $name]);
            }
        }

        return $columns;
    }
}
