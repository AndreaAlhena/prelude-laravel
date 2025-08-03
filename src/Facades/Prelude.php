<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \PreludeSo\SDK\Services\LookupService lookup()
 * @method static \PreludeSo\SDK\Services\TransactionalService transactional()
 * @method static \PreludeSo\SDK\Services\VerificationService verification()
 * @method static \PreludeSo\SDK\Services\WatchService watch()
 *
 * @see \PreludeSo\SDK\PreludeClient
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

    /**
     * Get lookup service.
     */
    public static function lookup(): mixed
    {
        return static::getFacadeRoot()->lookup();
    }

    /**
     * Get transactional service.
     */
    public static function transactional(): mixed
    {
        return static::getFacadeRoot()->transactional();
    }

    /**
     * Get verification service.
     */
    public static function verification(): mixed
    {
        return static::getFacadeRoot()->verification();
    }

    /**
     * Get watch service.
     */
    public static function watch(): mixed
    {
        return static::getFacadeRoot()->watch();
    }
}