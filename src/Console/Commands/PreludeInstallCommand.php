<?php

namespace PreludeSo\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PreludeInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'prelude:install {--force : Overwrite existing configuration}';

    /**
     * The console command description.
     */
    protected $description = 'Install and configure the Prelude Laravel package';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Installing Prelude Laravel package...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--tag' => 'prelude-config',
            '--force' => $this->option('force'),
        ]);

        // Add environment variables to .env if they don't exist
        $this->addEnvironmentVariables();

        $this->info('Prelude Laravel package installed successfully!');
        $this->line('');
        $this->line('Next steps:');
        $this->line('1. Add your Prelude API key to the PRELUDE_API_KEY environment variable');
        $this->line('2. Optionally configure other settings in config/prelude.php');
        $this->line('3. Start using the Prelude facade or inject PreludeClient in your classes');

        return self::SUCCESS;
    }

    /**
     * Add environment variables to .env file if they don't exist.
     */
    protected function addEnvironmentVariables(): void
    {
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            $this->warn('.env file not found. Please create one and add the required environment variables.');
            return;
        }

        $envContent = File::get($envPath);
        $newVariables = [];

        $variables = [
            'PRELUDE_API_KEY' => '',
            'PRELUDE_BASE_URL' => 'https://api.prelude.so',
            'PRELUDE_TIMEOUT' => '30',
        ];

        foreach ($variables as $key => $defaultValue) {
            if (!str_contains($envContent, $key . '=')) {
                $newVariables[] = $key . '=' . $defaultValue;
            }
        }

        if (!empty($newVariables)) {
            $envContent .= "\n\n# Prelude Configuration\n" . implode("\n", $newVariables) . "\n";
            File::put($envPath, $envContent);
            $this->info('Added Prelude environment variables to .env file.');
        }
    }
}