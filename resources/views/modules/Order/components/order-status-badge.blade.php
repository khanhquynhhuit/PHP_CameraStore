@props(['status'])

@php
    $classes = match($status) {
        'completed' => 'bg-green-100 text-green-800',
        'processing', 'shipping' => 'bg-blue-100 text-blue-800',
        'cancelled' => 'bg-red-100 text-red-800',
        default => 'bg-yellow-100 text-yellow-800',
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
