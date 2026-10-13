<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-heading font-semibold tracking-tight text-xl text-ink leading-tight flex items-center gap-2">
                <span>🛒</span>
                {{ __('Giỏ hàng của bạn') }}
            </h2>
            @if (($totalItems ?? 0) > 0)
                <form method="POST" action="{{ route('cart.clear') }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <x-button variant="danger" size="sm" type="submit" onclick="return confirm('Bạn có chắc muốn xóa tất cả sản phẩm trong giỏ hàng?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Xóa tất cả</span>
                    </x-button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('modules.Cart.components.cart-selection')
        </div>
    </div>
</x-app-layout>
