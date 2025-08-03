<?php

namespace PreludeSo\Laravel\Tests;

use Orchestra\Testbench\TestCase;
use PreludeSo\Laravel\PreludeServiceProvider;
use PreludeSo\Sdk\PreludeClient;

class PreludeServiceProviderTest extends TestCase
{
    /**
     * Get package providers.
     */
    protected function getPackageProviders($app): array
    {
        return [
            PreludeServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('prelude.api_key', 'test-api-key');
        $app['config']->set('prelude.base_url', 'https://api.prelude.so');
        $app['config']->set('prelude.timeout', 30);
    }

    /**
     * Test that the service provider registers the Prelude client.
     */
    public function test_prelude_client_is_registered(): void
    {
        $client = $this->app->make(PreludeClient::class);
        
        $this->assertInstanceOf(PreludeClient::class, $client);
    }

    /**
     * Test that the Prelude client can be resolved via alias.
     */
    public function test_prelude_client_can_be_resolved_via_alias(): void
    {
        $client = $this->app->make('prelude');
        
        $this->assertInstanceOf(PreludeClient::class, $client);
    }

    /**
     * Test that the same instance is returned (singleton).
     */
    public function test_prelude_client_is_singleton(): void
    {
        $client1 = $this->app->make(PreludeClient::class);
        $client2 = $this->app->make(PreludeClient::class);
        
        $this->assertSame($client1, $client2);
    }

    /**
     * Test that configuration is merged correctly.
     */
    public function test_configuration_is_merged(): void
    {
        $config = $this->app['config']['prelude'];
        
        $this->assertEquals('test-api-key', $config['api_key']);
        $this->assertEquals('https://api.prelude.so', $config['base_url']);
        $this->assertEquals(30, $config['timeout']);
    }
}