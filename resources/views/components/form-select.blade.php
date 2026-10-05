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
