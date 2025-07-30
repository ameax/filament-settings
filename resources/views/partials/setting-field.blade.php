@php
    $type = $setting['type'] ?? 'string';
    $originalKey = $setting['original_key'] ?? $key;
    $label = isset($setting['label']) ? __($setting['label']) : Str::title(str_replace(['_', '.'], ' ', $originalKey));
    $value = $data[$key] ?? $setting['default'] ?? null;
    $required = isset($setting['validation']) && str_contains($setting['validation'], 'required');
    $placeholder = isset($setting['placeholder']) ? __($setting['placeholder']) : null;
    $helper = isset($setting['helper']) ? __($setting['helper']) : null;
@endphp

@if(!($setting['hidden'] ?? false))
    <div>
        @switch($type)
            @case('boolean')
                <label class="flex items-center space-x-3">
                    <input type="checkbox" 
                        wire:model="data.{{ $key }}"
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-600 @error('data.' . $key) border-danger-600 @enderror"
                    />
                    <span>{{ $label }}</span>
                    @if($required) <span class="text-danger-600">*</span> @endif
                </label>
                @error('data.' . $key)
                    <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                @enderror
                @break

            @case('select')
                <label class="block text-sm font-medium mb-2">
                    {{ $label }}
                    @if($required) <span class="text-danger-600">*</span> @endif
                </label>
                <select wire:model="data.{{ $key }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('data.' . $key) border-danger-600 @enderror">
                    <option value="">{{ __('filament-settings::settings.select') ?? 'Select...' }}</option>
                    @foreach($setting['options'] ?? [] as $optionKey => $optionLabel)
                        <option value="{{ $optionKey }}">{{ __($optionLabel) }}</option>
                    @endforeach
                </select>
                @error('data.' . $key)
                    <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                @enderror
                @break

            @case('textarea')
                <label class="block text-sm font-medium mb-2">
                    {{ $label }}
                    @if($required) <span class="text-danger-600">*</span> @endif
                </label>
                <textarea 
                    wire:model="data.{{ $key }}"
                    rows="3"
                    @if($placeholder) placeholder="{{ $placeholder }}" @endif
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('data.' . $key) border-danger-600 @enderror"
                ></textarea>
                @error('data.' . $key)
                    <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                @enderror
                @break

            @case('encrypted')
                <label class="block text-sm font-medium mb-2">
                    {{ $label }}
                    @if($required) <span class="text-danger-600">*</span> @endif
                </label>
                <input 
                    type="password"
                    wire:model="data.{{ $key }}"
                    @if($placeholder) placeholder="{{ $placeholder }}" @endif
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('data.' . $key) border-danger-600 @enderror"
                />
                @error('data.' . $key)
                    <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                @enderror
                @break

            @default
                <label class="block text-sm font-medium mb-2">
                    {{ $label }}
                    @if($required) <span class="text-danger-600">*</span> @endif
                </label>
                <input 
                    wire:model="data.{{ $key }}"
                    @if($type === 'email') type="email" @endif
                    @if($type === 'url') type="url" @endif
                    @if($type === 'integer' || $type === 'float') type="number" @endif
                    @if($type === 'float') step="0.01" @endif
                    @if($placeholder) placeholder="{{ $placeholder }}" @endif
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('data.' . $key) border-danger-600 @enderror"
                />
                @if($helper)
                    <p class="text-sm text-gray-600 mt-1">{{ $helper }}</p>
                @endif
                @error('data.' . $key)
                    <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                @enderror
        @endswitch
    </div>
@endif