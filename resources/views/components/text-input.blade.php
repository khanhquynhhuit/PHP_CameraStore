@props(['disabled' => false])

@php
    $name = $attributes->get('name');
    $hasError = $name && $errors->has($name);
    
    $classes = $hasError
        ? 'border-danger-ink/50 focus:border-danger-ink focus:ring-danger bg-danger/20 text-danger-ink rounded-lg shadow-sm'
        : 'border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm';
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>
