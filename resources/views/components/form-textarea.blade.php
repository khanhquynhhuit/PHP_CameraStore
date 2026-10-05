@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'rows' => 4,
    'disabled' => false,
])

@php
    $hasError = $errors->has($name);
    $inputValue = old($name, $value);
    
    $textareaClasses = $hasError
        ? 'border-red-400 focus:border-red-500 focus:ring-red-500 bg-red-50/20 text-red-900 rounded-md shadow-sm block w-full mt-1'
        : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1';
@endphp

<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if ($label)
        <label for="{{ $name }}" class="block font-medium text-sm text-gray-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 font-bold">*</span>
            @endif
        </label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @required($required)
        @disabled($disabled)
        class="{{ $textareaClasses }}"
    >{{ $inputValue }}</textarea>

    <x-input-error :for="$name" />
</div>
