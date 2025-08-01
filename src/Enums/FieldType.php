<?php

namespace Ameax\FilamentSettings\Enums;

enum FieldType: string
{
    case BOOLEAN = 'boolean';
    case SELECT = 'select';
    case CHECKBOX = 'checkbox';
    case TEXTAREA = 'textarea';
    case ENCRYPTED = 'encrypted';
    case TEXT = 'text';
    case STRING = 'string';
    case EMAIL = 'email';
    case URL = 'url';
    case INTEGER = 'integer';
    case FLOAT = 'float';

    public function getViewPath(): string
    {
        return match($this) {
            self::BOOLEAN => 'filament-settings::fields.boolean',
            self::SELECT => 'filament-settings::fields.select',
            self::CHECKBOX => 'filament-settings::fields.checkbox',
            self::TEXTAREA => 'filament-settings::fields.textarea',
            self::ENCRYPTED => 'filament-settings::fields.encrypted',
            self::TEXT, self::STRING, self::EMAIL, self::URL, self::INTEGER, self::FLOAT => 'filament-settings::fields.text',
        };
    }

    public function getInputType(): string
    {
        return match($this) {
            self::EMAIL => 'email',
            self::URL => 'url',
            self::INTEGER, self::FLOAT => 'number',
            default => 'text',
        };
    }

    public function getStep(): ?string
    {
        return match($this) {
            self::FLOAT => '0.01',
            default => null,
        };
    }
}