@props(['status'])

@php
    $classes = match($status) {
        'completed' => 'bg-success text-success-ink border border-success-ink/20',
        'processing', 'shipping' => 'bg-info text-info-ink border border-info-ink/20',
        'cancelled' => 'bg-danger text-danger-ink border border-danger-ink/20',
        default => 'bg-warning text-warning-ink border border-warning-ink/20',
    };

    $label = match($status) {
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipping' => 'Đang giao hàng',
        'completed' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy',
        default => ucfirst($status),
    };
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $label }}
</span>
