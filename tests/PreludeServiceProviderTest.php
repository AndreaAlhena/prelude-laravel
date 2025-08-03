<?php

declare(strict_types=1);

use PreludeSo\SDK\PreludeClient;

test('can resolve Prelude client via alias', function (): void {
    $client = app('prelude');
    
    expect($client)->toBeInstanceOf(PreludeClient::class);
})->group('integration');

test('merges configuration correctly', function (): void {
    $config = app('config')->get('prelude');
    
    expect($config['api_key'])->toBe('test-api-key');
    expect($config['base_url'])->toBe('https://api.prelude.so');
    expect($config['timeout'])->toBe(30);
})->group('configuration');

test('registers the Prelude client', function (): void {
    $client = app(PreludeClient::class);
    
    expect($client)->toBeInstanceOf(PreludeClient::class);
})->group('integration');

test('returns the same instance (singleton)', function (): void {
    $client1 = app(PreludeClient::class);
    $client2 = app(PreludeClient::class);
    
    expect($client1)->toBe($client2);
})->group('integration');