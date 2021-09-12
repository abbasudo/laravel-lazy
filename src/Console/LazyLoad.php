<?php

namespace Llabbasmkhll\LaravelLazy\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LazyLoad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazy:load {name} {--M|model} {--a|admin} {--c|client} {--m|migration} {--s|seeder} {--f|factory} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD operations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getStub($type)
    {
        return file_get_contents(__DIR__."/../stubs/$type.stub");
    }

    protected function getControllerStub($type)
    {
        return file_get_contents(__DIR__."/../stubs/controllers/{$type}Controller.stub");
    }

    protected function model($name)
    {
        $modelTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/Models/{$name}.php"), $modelTemplate);
    }

    protected function admin_controller($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralUpperCase}}',
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name),
                Str::plural($name),
            ],
            $this->getControllerStub('Admin')
        );

        File::ensureDirectoryExists(app_path('/Http/Controllers/Admin'));

        file_put_contents(
            app_path('/Http/Controllers/Admin/'.$name.'Controller.php'),
            $controllerTemplate
        );
    }

    protected function client_controller($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralUpperCase}}',
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name),
                Str::plural($name),
            ],
            $this->getControllerStub('Client')
        );

        File::ensureDirectoryExists(app_path('/Http/Controllers/Client'));

        file_put_contents(
            app_path('/Http/Controllers/Client/'.$name.'Controller.php'),
            $controllerTemplate
        );
    }

    protected function migration($name)
    {
        $migrationTemplate = str_replace(
            [
                '{{modelNamePluralUpperCase}}',
                '{{modelNamePluralLowerCase}}',
            ],
            [
                Str::plural($name),
                strtolower(Str::plural($name)),
            ],
            $this->getStub('Migration')
        );

        $name = strtolower(Str::plural($name));
        $date = date('Y_m_d_His');

        $fileName = $date.'_create_'.$name.'_table.php';
        file_put_contents(
            database_path('/migrations/'.$fileName),
            $migrationTemplate
        );

        return $fileName;
    }


    public function seeder($name)
    {
        $seederTemplate = str_replace(
            [
                '{{modelName}}',
            ],
            [
                $name,
            ],
            $this->getStub('Seeder')
        );

        file_put_contents(
            database_path("/seeders/{$name}Seeder.php"),
            $seederTemplate
        );
    }


    public function factory($name)
    {
        $factoryTemplate = str_replace(
            [
                '{{modelName}}',
            ],
            [
                $name,
            ],
            $this->getStub('Factory')
        );

        file_put_contents(
            database_path("/factories/{$name}Factory.php"),
            $factoryTemplate
        );
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = Str::ucfirst($this->argument('name'));

        if ($this->option('model')) {
            $this->model($name);
            $this->info($name.'model created!');
        }

        if ($this->option('admin')) {
            $this->admin_controller($name);
            $this->info('Admin/'.$name.'Controller created!');
        }

        if ($this->option('client')) {
            $this->client_controller($name);
            $this->info('Client/'.$name.'Controller created!');
        }

        if ($this->option('migrate')) {
            $fileName = $this->migration($name);
            $this->info($fileName.' created!');
        }

        if ($this->option('factory')) {
            $this->factory($name);
            $this->info($name.' factory created!');
        }

        if ($this->option('seeder')) {
            $this->seeder($name);
            $this->info($name.' seeder created!');
        }

        //        File::append(base_path('routes / api.php'),
        //            "\n".'Route::apiResource(\'/'.str::plural(strtolower($name))
        //            .'\', \App\Http\Controllers\Admin\\'.$name.'Controller::class);'
        //            ."\n".
        //            "\n".'Route::apiResource(\'/'.str::plural(strtolower($name))
        //            .'\', \App\Http\Controllers\Client\\'.$name.'Controller::class);'
        //            ."\n"
        //        );


        $this->info($name.' files created successfully!');

        return 0;
    }
}
