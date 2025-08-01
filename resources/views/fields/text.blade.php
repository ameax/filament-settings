@php
    $inputType = 'text';
    $step = null;
    
    if ($type === 'email') {
        $inputType = 'email';
    } elseif ($type === 'url') {
        $inputType = 'url';
    } elseif ($type === 'integer' || $type === 'float') {
        $inputType = 'number';
        if ($type === 'float') {
            $step = '0.01';
        }
    }
@endphp

<label class="block text-sm font-medium mb-2">
    {{ $label }}
    @if($required) <span class="text-danger-600">*</span> @endif
</label>
<input 
    wire:model="data.{{ $key }}"
    type="{{ $inputType }}"
    @if($step) step="{{ $step }}" @endif
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('data.' . $key) border-danger-600 @enderror"
/>
@if($helper)
    <p class="text-sm text-gray-600 mt-1">{{ $helper }}</p>
@endif
@error('data.' . $key)
    <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
@enderror