@props(['disabled' => false])

@php
    $name = $attributes->get('name');
    $hasError = $name && $errors->has($name);
    
    $classes = $hasError
        ? 'border-red-400 focus:border-red-500 focus:ring-red-500 bg-red-50/20 text-red-900 rounded-md shadow-sm'
        : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm';
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>
