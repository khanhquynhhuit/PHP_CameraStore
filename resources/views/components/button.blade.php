@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'href' => null])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-heading font-semibold rounded-lg transition-colors duration-150
             focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2
             disabled:opacity-50 disabled:cursor-not-allowed';

    $variants = [
        'primary'       => 'bg-primary text-cream hover:bg-primary-dark',
        'accent'        => 'bg-accent text-ink hover:brightness-95',
        'secondary'     => 'bg-surface text-ink border border-ink/10 hover:bg-ink/5',
        'outline'       => 'border border-primary text-primary-dark hover:bg-primary hover:text-cream',
        'outline-light' => 'border border-cream/50 text-cream hover:bg-cream/10',
        'danger'        => 'bg-danger text-danger-ink hover:brightness-95',
        'ghost'         => 'text-ink hover:bg-ink/5',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
        {{ $slot }}
    </button>
@endif
