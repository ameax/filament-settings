<?php

namespace Ameax\FilamentSettings\Services;

use Ameax\FilamentSettings\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected static array $cache = [];

    protected static bool $loaded = false;

    public static function get(string $key, mixed $default = null): mixed
    {
        self::loadCache();

        return self::$cache[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $definition = self::getDefinition($key);

        $setting = Setting::query()->firstOrNew(
            ['key' => $key],
            [
                'type' => $definition['type'] ?? 'string',
                'group' => $definition['group'] ?? 'general',
                'tab' => $definition['tab'] ?? null,
                'order' => $definition['order'] ?? 0,
                'metadata' => $definition,
            ]
        );

        $setting->value = $value;
        $setting->save();

        self::$cache[$key] = $value;
        Cache::forget('filament-settings');
    }

    public static function getByGroup(string $group): array
    {
        self::loadCache();

        $settings = [];
        $definitions = config('filament-settings.definitions.'.$group.'.settings', []);

        foreach ($definitions as $key => $definition) {
            $settings[$key] = self::get($key, $definition['default'] ?? null);
        }

        return $settings;
    }

    public static function loadCache(bool $force = false): void
    {
        if (! $force && self::$loaded) {
            return;
        }

        self::$cache = Cache::remember('filament-settings', 3600, function () {
            $settings = [];

            foreach (Setting::all() as $setting) {
                $settings[$setting->key] = $setting->value;
            }

            return $settings;
        });

        self::$loaded = true;
    }

    public static function clearCache(): void
    {
        Cache::forget('filament-settings');
        self::$cache = [];
        self::$loaded = false;
    }

    protected static function getDefinition(string $key): array
    {
        $parts = explode('.', $key);
        $group = $parts[0] ?? 'general';

        $groups = config('filament-settings.definitions', []);

        foreach ($groups as $groupKey => $groupData) {
            if (isset($groupData['settings'][$key])) {
                return array_merge(
                    $groupData['settings'][$key],
                    ['group' => $groupKey]
                );
            }
        }

        return ['group' => $group];
    }

    public static function getAllDefinitions(): array
    {
        return config('filament-settings.definitions', []);
    }

    public static function validate(string $key, mixed $value): bool
    {
        $definition = self::getDefinition($key);

        if (! isset($definition['validation'])) {
            return true;
        }

        $validator = validator(['value' => $value], ['value' => $definition['validation']]);

        return ! $validator->fails();
    }
}