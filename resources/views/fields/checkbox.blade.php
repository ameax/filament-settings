<label class="block text-sm font-medium mb-2">
    {{ $label }}
    @if($required) <span class="text-danger-600">*</span> @endif
</label>
<div class="space-y-2">
    @foreach($setting['options'] ?? [] as $optionKey => $optionLabel)
        <label class="flex items-center space-x-3">
            <input type="checkbox" 
                wire:model="data.{{ $key }}" 
                value="{{ $optionKey }}"
                class="rounded border-gray-300 text-primary-600 focus:ring-primary-600 @error('data.' . $key) border-danger-600 @enderror"
            />
            <span>{{ __($optionLabel) }}</span>
        </label>
    @endforeach
</div>
@error('data.' . $key)
    <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
@enderror