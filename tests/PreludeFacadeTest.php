<?php

declare(strict_types=1);

use PreludeSo\Laravel\Facades\Prelude;
use PreludeSo\SDK\Services\LookupService;
use PreludeSo\SDK\Services\TransactionalService;
use PreludeSo\SDK\Services\VerificationService;
use PreludeSo\SDK\Services\WatchService;
use PreludeSo\SDK\Services\WebhookService;

test('facade returns lookup service instance', function (): void {
    $service = Prelude::lookup();
    
    expect($service)->toBeInstanceOf(LookupService::class);
})->group('facade');

test('facade returns transactional service instance', function (): void {
    $service = Prelude::transactional();
    
    expect($service)->toBeInstanceOf(TransactionalService::class);
})->group('facade');

test('facade returns verification service instance', function (): void {
    $service = Prelude::verification();
    
    expect($service)->toBeInstanceOf(VerificationService::class);
})->group('facade');

test('facade returns watch service instance', function (): void {
    $service = Prelude::watch();
    
    expect($service)->toBeInstanceOf(WatchService::class);
})->group('facade');

test('facade returns webhook service instance', function (): void {
    $service = Prelude::webhook();
    
    expect($service)->toBeInstanceOf(WebhookService::class);
})->group('facade');

test('facade returns same service instances on multiple calls', function (): void {
    $lookup1 = Prelude::lookup();
    $lookup2 = Prelude::lookup();
    $transactional1 = Prelude::transactional();
    $transactional2 = Prelude::transactional();
    $verification1 = Prelude::verification();
    $verification2 = Prelude::verification();
    $watch1 = Prelude::watch();
    $watch2 = Prelude::watch();
    $webhook1 = Prelude::webhook();
    $webhook2 = Prelude::webhook();
    
    expect($lookup1)->toBe($lookup2);
    expect($transactional1)->toBe($transactional2);
    expect($verification1)->toBe($verification2);
    expect($watch1)->toBe($watch2);
    expect($webhook1)->toBe($webhook2);
})->group('facade');

test('facade accessor returns correct string', function (): void {
    $reflection = new \ReflectionClass(Prelude::class);
    $method = $reflection->getMethod('getFacadeAccessor');
    $method->setAccessible(true);
    $accessor = $method->invoke(new Prelude());
    
    expect($accessor)->toBe('prelude');
})->group('facade');

test('all service methods return different service types', function (): void {
    $lookup = Prelude::lookup();
    $transactional = Prelude::transactional();
    $verification = Prelude::verification();
    $watch = Prelude::watch();
    $webhook = Prelude::webhook();
    
    // Ensure each service is a different type
    expect($lookup)->toBeInstanceOf(LookupService::class);
    expect($transactional)->toBeInstanceOf(TransactionalService::class);
    expect($verification)->toBeInstanceOf(VerificationService::class);
    expect($watch)->toBeInstanceOf(WatchService::class);
    expect($webhook)->toBeInstanceOf(WebhookService::class);
    
    // Ensure they are different instances (different service types)
    expect($lookup)->not->toBe($transactional);
    expect($lookup)->not->toBe($verification);
    expect($lookup)->not->toBe($watch);
    expect($lookup)->not->toBe($webhook);
    expect($transactional)->not->toBe($verification);
    expect($transactional)->not->toBe($watch);
    expect($transactional)->not->toBe($webhook);
    expect($verification)->not->toBe($watch);
    expect($verification)->not->toBe($webhook);
    expect($watch)->not->toBe($webhook);
})->group('facade');