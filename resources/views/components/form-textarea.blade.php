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
        ? 'border-danger-ink/50 focus:border-danger-ink focus:ring-danger-ink bg-danger/30 text-danger-ink rounded-lg shadow-sm block w-full mt-1'
        : 'border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm block w-full mt-1';
@endphp

<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if ($label)
        <label for="{{ $name }}" class="block font-medium text-sm text-ink">
            {{ $label }}
            @if ($required)
                <span class="text-danger-ink font-bold">*</span>
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
