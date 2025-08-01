<?php

namespace Ameax\FilamentSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'tab',
        'order',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function getValueAttribute(?string $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($this->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'json', 'array', 'checkbox' => json_decode($value, true),
            'encrypted' => $this->decryptValue($value),
            default => $value,
        };
    }

    public function setValueAttribute(mixed $value): void
    {
        if ($value === null) {
            $this->attributes['value'] = null;

            return;
        }

        $this->attributes['value'] = match ($this->type) {
            'boolean' => $value ? '1' : '0',
            'json', 'array', 'checkbox' => json_encode($value),
            'encrypted' => Crypt::encryptString((string) $value),
            default => (string) $value,
        };
    }

    public function getRawValueAttribute(): ?string
    {
        return $this->attributes['value'] ?? null;
    }

    protected function decryptValue(string $value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }
}