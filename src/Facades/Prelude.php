<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use PreludeSo\SDK\Services\{LookupService, TransactionalService, VerificationService, WatchService, WebhookService};

/**
 * @method static \PreludeSo\SDK\Services\LookupService lookup()
 * @method static \PreludeSo\SDK\Services\TransactionalService transactional()
 * @method static \PreludeSo\SDK\Services\VerificationService verification()
 * @method static \PreludeSo\SDK\Services\WatchService watch()
 * @method static \PreludeSo\SDK\Services\WebhookService webhook()
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
    public static function lookup(): LookupService
    {
        return static::getFacadeRoot()->lookup();
    }

    /**
     * Get transactional service.
     */
    public static function transactional(): TransactionalService
    {
        return static::getFacadeRoot()->transactional();
    }

    /**
     * Get verification service.
     */
    public static function verification(): VerificationService
    {
        return static::getFacadeRoot()->verification();
    }

    /**
     * Get watch service.
     */
    public static function watch(): WatchService
    {
        return static::getFacadeRoot()->watch();
    }

    /**
     * Get webhook service.
     */
    public static function webhook(): \PreludeSo\SDK\Services\WebhookService
    {
        return static::getFacadeRoot()->webhook();
    }
}