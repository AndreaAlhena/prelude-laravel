<?php

declare(strict_types=1);

namespace PreludeSo\Laravel;

use Illuminate\Support\ServiceProvider;

use PreludeSo\Laravel\Console\Commands\PreludeInstallCommand;
use PreludeSo\SDK\PreludeClient;

class PreludeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->_publishConfiguration();
            $this->_registerCommands();
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            'prelude',
            PreludeClient::class,
        ];
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->_mergeConfiguration();
        $this->_registerClient();
        $this->_registerAlias();
    }

    /**
     * Merge package configuration.
     */
    private function _mergeConfiguration(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/prelude.php',
            'prelude'
        );
    }

    /**
     * Publish configuration file.
     */
    private function _publishConfiguration(): void
    {
        $this->publishes([
            __DIR__.'/../config/prelude.php' => config_path('prelude.php'),
        ], 'prelude-config');
    }

    /**
     * Register client alias.
     */
    private function _registerAlias(): void
    {
        $this->app->alias(PreludeClient::class, 'prelude');
    }

    /**
     * Register Prelude client singleton.
     */
    private function _registerClient(): void
    {
        $this->app->singleton(PreludeClient::class, function ($app): PreludeClient {
            $config = $app['config']['prelude'];
            
            // Create client with API key and optional base URL
            if (!empty($config['base_url']) && $config['base_url'] !== 'https://api.prelude.so') {
                return new PreludeClient($config['api_key'], $config['base_url']);
            }
            
            return new PreludeClient($config['api_key']);
        });
    }

    /**
     * Register console commands.
     */
    private function _registerCommands(): void
    {
        $this->commands([
            PreludeInstallCommand::class,
        ]);
    }
}