<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use PreludeSo\Laravel\PreludeServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     */
    protected function getPackageProviders(mixed $app): array
    {
        return [
            PreludeServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     */
    protected function defineEnvironment(mixed $app): void
    {
        $app['config']->set('prelude.api_key', 'test-api-key');
        $app['config']->set('prelude.base_url', 'https://api.prelude.so');
        $app['config']->set('prelude.timeout', 30);
    }
}