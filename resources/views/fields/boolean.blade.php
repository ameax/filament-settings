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