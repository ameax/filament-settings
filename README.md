# Ameax Filament Settings

A flexible settings management system for Laravel with Filament integration.

## Features

- 🔧 Dynamic settings management with type casting
- 🔐 Built-in encryption support for sensitive data
- 📦 Caching for optimal performance
- 🎨 Beautiful Filament UI with tabs and groups
- ✅ Validation support
- 🌐 Translation ready
- 🔍 Easy to extend and customize

## Installation

### 1. Install via Composer

```bash
composer require ameax/filament-settings
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --tag=filament-settings-config
```

### 3. Run Migrations

```bash
php artisan vendor:publish --tag=filament-settings-migrations
php artisan migrate
```

### 4. (Optional) Publish Views

If you want to customize the views:

```bash
php artisan vendor:publish --tag=filament-settings-views
```

## Configuration

Edit `config/filament-settings.php` to define your settings:

```php
return [
    'definitions' => [
        'shop' => [
            'label' => 'Shop Settings',
            'icon' => 'heroicon-o-shopping-bag',
            'order' => 1,
            'settings' => [
                'shop.name' => [
                    'label' => 'Shop Name',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'tab' => 'general',
                    'order' => 1,
                    'helper' => 'Your shop display name',
                    'placeholder' => 'My Awesome Shop',
                ],
                'shop.email' => [
                    'label' => 'Contact Email',
                    'type' => 'email',
                    'validation' => 'required|email',
                    'tab' => 'general',
                    'order' => 2,
                ],
                'shop.maintenance_mode' => [
                    'label' => 'Maintenance Mode',
                    'type' => 'boolean',
                    'tab' => 'advanced',
                    'default' => false,
                ],
            ],
        ],
    ],
];
```

### Available Field Types

- `string` - Text input
- `email` - Email input
- `url` - URL input
- `integer` - Number input (whole numbers)
- `float` - Number input (decimals)
- `boolean` - Checkbox
- `select` - Dropdown selection
- `textarea` - Multi-line text
- `encrypted` - Password input with encryption
- `json` / `array` - JSON data (automatically encoded/decoded)

## Usage

### In Filament Admin Panel

The settings page will automatically appear in your Filament admin panel. You can customize its position using the `navigationSort` property.

### In Your Application

```php
use Ameax\FilamentSettings\Facades\Settings;

// Get a setting
$shopName = Settings::get('shop.name', 'Default Shop');

// Set a setting
Settings::set('shop.email', 'contact@example.com');

// Get all settings in a group
$shopSettings = Settings::getByGroup('shop');

// Clear cache after bulk updates
Settings::clearCache();
```

### In Blade Views

```blade
<h1>{{ \Ameax\FilamentSettings\Facades\Settings::get('shop.name') }}</h1>
```

## Advanced Usage

### Custom Validation

```php
'settings' => [
    'api.key' => [
        'label' => 'API Key',
        'type' => 'encrypted',
        'validation' => 'required|string|min:32',
        'helper' => 'Your secure API key',
    ],
],
```

### Tabs Organization

Group related settings using tabs:

```php
'settings' => [
    'shop.name' => [
        'tab' => 'general',
        // ...
    ],
    'shop.tax_rate' => [
        'tab' => 'financial',
        // ...
    ],
    'shop.api_key' => [
        'tab' => 'advanced',
        // ...
    ],
],
```

### Custom Select Options

```php
'shop.currency' => [
    'label' => 'Currency',
    'type' => 'select',
    'options' => [
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'GBP' => 'British Pound',
    ],
    'default' => 'USD',
],
```

## Extending

### Custom Setting Types

You can extend the Setting model to add custom types:

```php
namespace App\Models;

use Ameax\FilamentSettings\Models\Setting as BaseSettings;

class Setting extends BaseSettings
{
    public function getValueAttribute($value)
    {
        if ($this->type === 'custom_type') {
            return $this->processCustomType($value);
        }
        
        return parent::getValueAttribute($value);
    }
}
```

Then update your service provider to use your custom model.

## Security

- Sensitive settings can use the `encrypted` type for automatic encryption
- All settings are validated before saving
- Proper authorization should be implemented in your Filament resources

## License

MIT