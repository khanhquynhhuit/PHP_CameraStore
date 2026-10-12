<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Giỏ hàng của bạn') }}
            </h2>
            @if (($totalItems ?? 0) > 0)
                <form method="POST" action="{{ route('cart.clear') }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Bạn có chắc muốn làm rỗng toàn bộ giỏ hàng?')"
                            class="text-xs sm:text-sm text-red-600 hover:text-red-800 hover:underline flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Xóa tất cả
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Danh sách sản phẩm trong giỏ -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm rounded-xl p-4 sm:p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900">Danh sách sản phẩm</h3>
                        <span class="text-xs text-gray-500 font-medium">Tự động cập nhật thành tiền</span>
                    </div>

                    @include('modules.Cart.components.cart-item')
                </div>

                <!-- Tóm tắt & Thanh toán -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-4 sm:p-6 h-fit border border-gray-100 sticky top-4">
                    @include('modules.Cart.components.cart-summary')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
