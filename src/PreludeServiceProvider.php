<?php

namespace PreludeSo\Laravel;

use Illuminate\Support\ServiceProvider;
use PreludeSo\Sdk\PreludeClient;
use PreludeSo\Laravel\Console\Commands\PreludeInstallCommand;

class PreludeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/prelude.php',
            'prelude'
        );

        $this->app->singleton(PreludeClient::class, function ($app) {
            $config = $app['config']['prelude'];
            
            return new PreludeClient([
                'api_key' => $config['api_key'],
                'base_url' => $config['base_url'],
                'timeout' => $config['timeout'],
            ]);
        });

        $this->app->alias(PreludeClient::class, 'prelude');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/prelude.php' => config_path('prelude.php'),
            ], 'prelude-config');

            $this->commands([
                PreludeInstallCommand::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            PreludeClient::class,
            'prelude',
        ];
    }
}