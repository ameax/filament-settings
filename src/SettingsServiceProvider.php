<?php

namespace Ameax\FilamentSettings;

use Ameax\FilamentSettings\Services\SettingsService;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/filament-settings.php', 'filament-settings'
        );

        $this->app->singleton('settings', function () {
            return new SettingsService();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/filament-settings.php' => config_path('filament-settings.php'),
            ], 'filament-settings-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'filament-settings-migrations');

            $this->publishes([
                __DIR__.'/../resources/views/' => resource_path('views/vendor/filament-settings'),
            ], 'filament-settings-views');

            $this->publishes([
                __DIR__.'/../resources/lang' => $this->app->langPath('vendor/filament-settings'),
            ], 'filament-settings-translations');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-settings');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-settings');
    }
}