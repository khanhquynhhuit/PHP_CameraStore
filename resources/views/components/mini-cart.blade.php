<div x-data="{ open: false, loading: false, cartData: null, fetched: false }" 
     @click.away="open = false" 
     class="relative">
    <!-- Trigger Button -->
    <a href="{{ route('cart.index') }}" 
       @mouseenter="
           if (!fetched) {
               loading = true;
               fetch('{{ route('cart.index') }}', {
                   headers: { 'Accept': 'application/json' }
               })
               .then(res => res.json())
               .then(data => {
                   if (data.status === 'success') {
                       cartData = data.data;
                       fetched = true;
                   }
               })
               .catch(err => console.error(err))
               .finally(() => loading = false);
           }
           open = true;
       "
       class="relative inline-flex items-center p-2 text-gray-600 hover:text-indigo-600 focus:outline-none transition rounded-lg hover:bg-gray-100"
       title="Giỏ hàng">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>

        @auth
            @php
                $itemCount = \App\Models\Cart::where('user_id', Auth::id())
                    ->with('items')
                    ->first()
                    ?->items
                    ?->sum('quantity') ?? 0;
            @endphp
            @if ($itemCount > 0)
                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-indigo-600 rounded-full">
                    {{ $itemCount > 99 ? '99+' : $itemCount }}
                </span>
            @endif
        @endauth
    </a>

    <!-- Dropdown Panel -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
         style="display: none;">
        
        <div class="p-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <span class="text-sm font-bold text-gray-800">Giỏ hàng của bạn</span>
            <a href="{{ route('cart.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">Xem tất cả</a>
        </div>

        <!-- Nội dung đang tải -->
        <template x-if="loading">
            <div class="p-6 text-center text-sm text-gray-500">
                <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Đang tải giỏ hàng...
            </div>
        </template>

        <!-- Danh sách items -->
        <template x-if="!loading && cartData && cartData.items.length > 0">
            <div>
                <div class="max-h-64 overflow-y-auto divide-y divide-gray-100 px-3">
                    <template x-for="item in cartData.items" :key="item.id">
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-10 h-10 rounded bg-gray-100 shrink-0 flex items-center justify-center text-xs text-gray-400 overflow-hidden">
                                    <template x-if="item.product.product_images && item.product.product_images.length > 0">
                                        <img :src="'/storage/' + item.product.product_images[0].image_url" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!item.product.product_images || item.product.product_images.length === 0">
                                        <span>📷</span>
                                    </template>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-800 truncate" x-text="item.product.name"></p>
                                    <p class="text-xs text-gray-500">
                                        <span x-text="item.quantity"></span> x <span class="text-indigo-600 font-medium" x-text="new Intl.NumberFormat('vi-VN').format(item.product.price) + ' đ'"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-3 bg-gray-50 border-t border-gray-100">
                    <div class="flex justify-between items-center text-sm mb-3">
                        <span class="text-gray-600 font-medium">Tổng cộng:</span>
                        <span class="text-base font-bold text-indigo-600" x-text="new Intl.NumberFormat('vi-VN').format(cartData.total) + ' đ'"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('cart.index') }}" class="py-2 px-3 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs font-bold rounded-lg transition">
                            Vào giỏ hàng
                        </a>
                        <a href="{{ route('checkout.index') }}" class="py-2 px-3 text-center bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition">
                            Thanh toán
                        </a>
                    </div>
                </div>
            </div>
        </template>

        <!-- Khi giỏ rỗng -->
        <template x-if="!loading && (!cartData || cartData.items.length === 0)">
            <div class="p-6 text-center text-sm text-gray-500">
                <p class="mb-2">Chưa có sản phẩm nào trong giỏ</p>
                <a href="{{ route('products.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">Khám phá sản phẩm</a>
            </div>
        </template>
    </div>
</div>
