<?php

namespace PreludeSo\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use PreludeSo\Sdk\PreludeClient;

/**
 * @method static mixed get(string $endpoint, array $params = [])
 * @method static mixed post(string $endpoint, array $data = [])
 * @method static mixed put(string $endpoint, array $data = [])
 * @method static mixed delete(string $endpoint, array $params = [])
 * @method static mixed patch(string $endpoint, array $data = [])
 * @method static self setTimeout(int $timeout)
 * @method static self setApiKey(string $apiKey)
 * @method static self setBaseUrl(string $baseUrl)
 *
 * @see \PreludeSo\Sdk\PreludeClient
 */
class Prelude extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'prelude';
    }
}