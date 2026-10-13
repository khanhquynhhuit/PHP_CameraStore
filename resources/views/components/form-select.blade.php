@props([
    'name',
    'label' => null,
    'selected' => null,
    'options' => [],
    'required' => false,
    'placeholder' => '--- Chọn ---',
    'disabled' => false,
])

@php
    $hasError = $errors->has($name);
    $currentValue = old($name, $selected);
    
    $selectClasses = $hasError
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

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @required($required)
        @disabled($disabled)
        class="{{ $selectClasses }}"
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if (!empty($options))
            @foreach ($options as $val => $text)
                <option value="{{ $val }}" @selected((string)$currentValue === (string)$val)>{{ $text }}</option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>

    <x-input-error :for="$name" />
</div>
