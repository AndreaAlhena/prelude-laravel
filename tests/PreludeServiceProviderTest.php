<?php

declare(strict_types=1);

use PreludeSo\Sdk\PreludeClient;

it('can resolve Prelude client via alias', function (): void {
    $client = $this->app->make('prelude');
    
    expect($client)->toBeInstanceOf(PreludeClient::class);
})->group('integration');

it('merges configuration correctly', function (): void {
    $config = $this->app['config']['prelude'];
    
    expect($config['api_key'])->toBe('test-api-key');
    expect($config['base_url'])->toBe('https://api.prelude.so');
    expect($config['timeout'])->toBe(30);
})->group('configuration');

it('registers the Prelude client', function (): void {
    $client = $this->app->make(PreludeClient::class);
    
    expect($client)->toBeInstanceOf(PreludeClient::class);
})->group('integration');

it('returns the same instance (singleton)', function (): void {
    $client1 = $this->app->make(PreludeClient::class);
    $client2 = $this->app->make(PreludeClient::class);
    
    expect($client1)->toBe($client2);
})->group('integration');