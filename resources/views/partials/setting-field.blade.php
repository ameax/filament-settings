@php
    use Ameax\FilamentSettings\Enums\FieldType;
    
    $type = $setting['type'] ?? 'string';
    $originalKey = $setting['original_key'] ?? $key;
    $label = isset($setting['label']) ? __($setting['label']) : Str::title(str_replace(['_', '.'], ' ', $originalKey));
    $value = $data[$key] ?? $setting['default'] ?? null;
    $required = isset($setting['validation']) && str_contains($setting['validation'], 'required');
    $placeholder = isset($setting['placeholder']) ? __($setting['placeholder']) : null;
    $helper = isset($setting['helper']) ? __($setting['helper']) : null;
    
    $fieldType = FieldType::tryFrom($type) ?? FieldType::TEXT;
    $viewPath = $fieldType->getViewPath();
@endphp

@if(!($setting['hidden'] ?? false))
    <div>
        @include($viewPath, [
            'key' => $key,
            'type' => $type,
            'setting' => $setting,
            'label' => $label,
            'value' => $value,
            'required' => $required,
            'placeholder' => $placeholder,
            'helper' => $helper
        ])
    </div>
@endif