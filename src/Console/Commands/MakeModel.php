<?php

namespace Dinesh\Magento\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModel extends Command
{
    protected $signature = 'magento:make-model {name}';
    protected $description = 'Create a new model for the magento package';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $modelPath = base_path('dinesh/magento/src/Models/' . $this->getDirectoryPath($name));

        // Ensure the directory exists
        if (!file_exists($modelPath)) {
            mkdir($modelPath, 0755, true);
        }

        $stubPath = __DIR__ . '/stubs/model.stub';
        $stub = file_get_contents($stubPath);
        $stub = str_replace('{{class}}', class_basename($name), $stub);
        
        $filePath = $modelPath . '/' . class_basename($name) . '.php';
        file_put_contents($filePath, $stub);

        $this->info('Model created successfully at ' . $filePath);
    }

    private function getDirectoryPath($name)
    {
        // Convert the name into a path
        $path = Str::replaceFirst('/', DIRECTORY_SEPARATOR, $name);
        $path = Str::replace('/', DIRECTORY_SEPARATOR, $path);

        return $path;
    }
}
