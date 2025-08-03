<?php

use PreludeSo\Sdk\PreludeClient;

it('registers the Prelude client', function () {
    $client = $this->app->make(PreludeClient::class);
    
    expect($client)->toBeInstanceOf(PreludeClient::class);
});

it('can resolve Prelude client via alias', function () {
    $client = $this->app->make('prelude');
    
    expect($client)->toBeInstanceOf(PreludeClient::class);
});

it('returns the same instance (singleton)', function () {
    $client1 = $this->app->make(PreludeClient::class);
    $client2 = $this->app->make(PreludeClient::class);
    
    expect($client1)->toBe($client2);
});

it('merges configuration correctly', function () {
    $config = $this->app['config']['prelude'];
    
    expect($config['api_key'])->toBe('test-api-key');
    expect($config['base_url'])->toBe('https://api.prelude.so');
    expect($config['timeout'])->toBe(30);
});