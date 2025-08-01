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