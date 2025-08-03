<?php

use PreludeSo\Laravel\PreludeServiceProvider;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(Orchestra\Testbench\TestCase::class)
    ->beforeEach(function () {
        // Configure the package providers
        $this->app->register(PreludeServiceProvider::class);
        
        // Set up test environment configuration
        config([
            'prelude.api_key' => 'test-api-key',
            'prelude.base_url' => 'https://api.prelude.so',
            'prelude.timeout' => 30,
        ]);
    })
    ->in('tests');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Get package providers for testing.
 */
function getPackageProviders($app): array
{
    return [
        PreludeServiceProvider::class,
    ];
}

/**
 * Define environment setup for testing.
 */
function defineEnvironment($app): void
{
    $app['config']->set('prelude.api_key', 'test-api-key');
    $app['config']->set('prelude.base_url', 'https://api.prelude.so');
    $app['config']->set('prelude.timeout', 30);
}