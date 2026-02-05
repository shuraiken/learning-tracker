<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan make:service UserService
     */
    protected $signature = 'make:service {name : The name of the service class}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new service class in app/Services';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $servicePath = app_path("Services/{$name}.php");

        // Ensure directory exists
        if (!File::exists(app_path('Services'))) {
            File::makeDirectory(app_path('Services'), 0755, true);
        }

        // Check if service already exists
        if (File::exists($servicePath)) {
            $this->error("Service {$name} already exists!");
            return Command::FAILURE;
        }

        // Build class content
        $stub = <<<PHP
<?php

namespace App\Services;

class {$name}
{
    public function __construct()
    {
        //
    }
}
PHP;

        // Write file
        File::put($servicePath, $stub);

        $this->info("Service {$name} created successfully.");

        return Command::SUCCESS;
    }
}
