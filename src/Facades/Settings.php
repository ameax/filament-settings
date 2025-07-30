<?php

namespace Ameax\FilamentSettings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, mixed $default = null)
 * @method static void set(string $key, mixed $value)
 * @method static array getByGroup(string $group)
 * @method static void loadCache(bool $force = false)
 * @method static void clearCache()
 * @method static array getAllDefinitions()
 * @method static bool validate(string $key, mixed $value)
 *
 * @see \Ameax\FilamentSettings\Services\SettingsService
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'settings';
    }
}