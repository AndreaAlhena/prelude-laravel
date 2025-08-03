<?php

namespace PreludeSo\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use PreludeSo\Sdk\PreludeClient;

/**
 * @method static \PreludeSo\Sdk\Services\VerificationService verification()
 * @method static \PreludeSo\Sdk\Services\LookupService lookup()
 * @method static \PreludeSo\Sdk\Services\TransactionalService transactional()
 * @method static \PreludeSo\Sdk\Services\WatchService watch()
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

    /**
     * Get verification service.
     *
     * @return mixed
     */
    public static function verification()
    {
        return static::getFacadeRoot()->verification();
    }

    /**
     * Get lookup service.
     *
     * @return mixed
     */
    public static function lookup()
    {
        return static::getFacadeRoot()->lookup();
    }

    /**
     * Get transactional service.
     *
     * @return mixed
     */
    public static function transactional()
    {
        return static::getFacadeRoot()->transactional();
    }

    /**
     * Get watch service.
     *
     * @return mixed
     */
    public static function watch()
    {
        return static::getFacadeRoot()->watch();
    }
}