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

        // Add environment variables to .env and .env.example if they don't exist
        $this->updateEnvironmentFile('.env');
        $this->updateEnvironmentFile('.env.example');

        $this->info('Prelude Laravel package installed successfully!');
        $this->line('');
        $this->line('Next steps:');
        $this->line('1. Add your Prelude API key to the PRELUDE_API_KEY environment variable');
        $this->line('2. Optionally configure other settings in config/prelude.php');
        $this->line('3. Start using the Prelude facade or inject PreludeClient in your classes');
        $this->line('4. Share the updated .env.example file with your team');

        return self::SUCCESS;
    }

    /**
     * Update environment file with Prelude variables.
     */
    protected function updateEnvironmentFile(string $filename): void
    {
        $filePath = base_path($filename);
        
        if (!File::exists($filePath)) {
            if ($filename === '.env') {
                $this->warn('.env file not found. Please create one and add the required environment variables.');
                $this->line('You can copy the .env.example file: cp .env.example .env');
                return;
            } else {
                $this->warn($filename . ' file not found. Creating one with Prelude variables.');
                $fileContent = "";
            }
        } else {
            $fileContent = File::get($filePath);
        }

        $newVariables = [];
        $variables = [
            'PRELUDE_API_KEY' => 'your_prelude_api_key_here',
            '# PRELUDE_BASE_URL' => 'https://api.prelude.so',
            '# PRELUDE_TIMEOUT' => '30',
        ];

        foreach ($variables as $key => $defaultValue) {
            $cleanKey = ltrim($key, '# ');
            if (!str_contains($fileContent, $cleanKey . '=')) {
                $newVariables[] = $key . '=' . $defaultValue;
            }
        }

        if (!empty($newVariables)) {
            $fileContent .= "\n\n# Prelude SDK Configuration\n# Get your API key from https://dashboard.prelude.so\n" . implode("\n", $newVariables) . "\n";
            File::put($filePath, $fileContent);
            $this->info('Updated ' . $filename . ' file with Prelude environment variables.');
            if ($filename === '.env') {
                $this->line('Remember to update PRELUDE_API_KEY with your actual API key.');
            }
        } else {
            $this->line('Prelude environment variables already exist in ' . $filename . ' file.');
        }
    }
}