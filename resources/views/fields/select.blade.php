<style>
    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
</style>

<label class="block text-sm font-medium mb-2">
    {{ $label }}
    @if($required)
        <span class="text-danger-600">*</span>
    @endif
</label>
<select wire:model="data.{{ $key }}"
        class="p-2 w-full rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('data.' . $key) border-danger-600 @enderror">
    <option value="">{{ __('filament-settings::settings.select') ?? 'Select...' }}</option>
    @foreach($setting['options'] ?? [] as $optionKey => $optionLabel)
        <option value="{{ $optionKey }}">{{ __($optionLabel) }}</option>
    @endforeach
</select>
@error('data.' . $key)
<p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
@enderror