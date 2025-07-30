<?php

namespace Ameax\FilamentSettings\Filament\Pages;

use Ameax\FilamentSettings\Facades\Settings as SettingsFacade;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = null;

    protected static ?string $title = null;

    protected static string $view = 'filament-settings::pages.manage-settings';

    protected static ?int $navigationSort = 900;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-settings::settings.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-settings::settings.title');
    }

    public function getTitle(): string
    {
        return __('filament-settings::settings.title');
    }

    public function getSubheading(): ?string
    {
        return __('filament-settings::settings.description');
    }

    public ?array $data = [];

    protected array $keyMapping = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $formData = [];
        $definitions = config('filament-settings.definitions', []);

        foreach ($definitions as $groupKey => $group) {
            foreach ($group['settings'] ?? [] as $key => $setting) {
                // Create a mapped key for Alpine.js compatibility
                $mappedKey = $this->getMappedKey($key);
                $this->keyMapping[$mappedKey] = $key;

                $formData[$mappedKey] = SettingsFacade::get($key, $setting['default'] ?? null);
            }
        }

        $this->data = $formData;
    }

    /**
     * Convert keys with dots to Alpine.js compatible format
     * Example: shop.name -> shopName, payment.paypal.email -> paymentPaypalEmail
     */
    protected function getMappedKey(string $key): string
    {
        $parts = explode('.', $key);
        $mapped = array_shift($parts);

        foreach ($parts as $part) {
            $mapped .= ucfirst($part);
        }

        return $mapped;
    }

    /**
     * Get the original key from mapped key
     */
    protected function getOriginalKey(string $mappedKey): string
    {
        return $this->keyMapping[$mappedKey] ?? $mappedKey;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label(__('filament-settings::settings.save'))
                ->action('saveSettings')
                ->color('primary')
                ->icon('heroicon-o-check'),
        ];
    }

    public function saveSettings(): void
    {
        $definitions = config('filament-settings.definitions', []);

        // Build validation rules
        $rules = [];
        $messages = [];
        $attributes = [];

        foreach ($definitions as $groupKey => $group) {
            foreach ($group['settings'] ?? [] as $key => $setting) {
                if (isset($setting['validation'])) {
                    $mappedKey = $this->getMappedKey($key);
                    $rules['data.'.$mappedKey] = $setting['validation'];
                    $attributes['data.'.$mappedKey] = __($setting['label']) ?? $key;
                }
            }
        }

        try {
            // Validate
            $this->validate($rules, $messages, $attributes);

            // Save settings
            foreach ($definitions as $groupKey => $group) {
                foreach ($group['settings'] ?? [] as $key => $setting) {
                    $mappedKey = $this->getMappedKey($key);
                    if (isset($this->data[$mappedKey])) {
                        // Save using the original key with dots
                        SettingsFacade::set($key, $this->data[$mappedKey]);
                    }
                }
            }

            SettingsFacade::clearCache();

            Notification::make()
                ->title(__('filament-settings::settings.saved'))
                ->success()
                ->send();

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Show notification about validation errors
            Notification::make()
                ->title(__('filament-settings::settings.validation_error'))
                ->body(__('filament-settings::settings.validation_error'))
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function getViewData(): array
    {
        $groups = config('filament-settings.definitions', []);

        // Create a mapping for the view
        $mappedGroups = [];
        foreach ($groups as $groupKey => $group) {
            $mappedGroups[$groupKey] = $group;
            $mappedGroups[$groupKey]['settings'] = [];

            foreach ($group['settings'] ?? [] as $key => $setting) {
                $mappedKey = $this->getMappedKey($key);
                $mappedGroups[$groupKey]['settings'][$mappedKey] = $setting;
                $mappedGroups[$groupKey]['settings'][$mappedKey]['original_key'] = $key;
            }
        }

        return [
            'groups' => $mappedGroups,
            'keyMapping' => $this->keyMapping,
        ];
    }
}