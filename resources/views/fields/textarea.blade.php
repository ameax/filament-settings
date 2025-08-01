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