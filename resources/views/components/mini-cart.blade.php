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
       class="relative flex items-center gap-2 p-2 px-3 text-gray-700 hover:text-red-600 focus:outline-none transition rounded-lg hover:bg-red-50 border border-gray-200"
       title="Giỏ hàng">
        <div class="relative">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-1.5 py-0.5 text-[11px] font-bold text-white bg-red-600 rounded-full border-2 border-white shadow-sm">
                        {{ $itemCount > 99 ? '99+' : $itemCount }}
                    </span>
                @endif
            @endauth
        </div>
        <div class="hidden lg:block text-left text-xs">
            <span class="text-gray-400 block text-[10px] uppercase font-bold">Giỏ hàng</span>
            <span class="font-bold text-gray-800">
                @auth
                    {{ $itemCount ?? 0 }} món
                @else
                    0 món
                @endauth
            </span>
        </div>
    </a>

    <!-- Dropdown Panel -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 overflow-hidden"
         style="display: none;">
        
        <div class="p-3 bg-red-50 border-b border-red-100 flex items-center justify-between">
            <span class="text-sm font-bold text-red-700 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.24-1.2A1 1 0 005.06 1H3z"/></svg>
                Giỏ hàng của bạn
            </span>
            <a href="{{ route('cart.index') }}" class="text-xs text-red-600 hover:underline font-bold">Xem giỏ hàng &rarr;</a>
        </div>

        <!-- Đang tải -->
        <template x-if="loading">
            <div class="p-6 text-center text-sm text-gray-500">
                <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Đang tải dữ liệu...
            </div>
        </template>

        <!-- Danh sách items -->
        <template x-if="!loading && cartData && cartData.items.length > 0">
            <div>
                <div class="max-h-64 overflow-y-auto divide-y divide-gray-100 px-3">
                    <template x-for="item in cartData.items" :key="item.id">
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-11 h-11 rounded border border-gray-100 bg-gray-50 shrink-0 flex items-center justify-center overflow-hidden">
                                    <template x-if="item.product.product_images && item.product.product_images.length > 0">
                                        <img :src="'/storage/' + item.product.product_images[0].image_url" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!item.product.product_images || item.product.product_images.length === 0">
                                        <span class="text-xs">📷</span>
                                    </template>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 truncate" x-text="item.product.name"></p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        <span x-text="item.quantity" class="font-bold"></span> x 
                                        <span class="text-red-600 font-bold" x-text="new Intl.NumberFormat('vi-VN').format(item.product.price) + ' đ'"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-3 bg-gray-50 border-t border-gray-100">
                    <div class="flex justify-between items-center text-sm mb-3">
                        <span class="text-gray-600 font-medium">Tổng tiền:</span>
                        <span class="text-base font-black text-red-600" x-text="new Intl.NumberFormat('vi-VN').format(cartData.total) + ' đ'"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('cart.index') }}" class="py-2 px-3 text-center bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold rounded-lg transition border border-gray-300">
                            Xem giỏ
                        </a>
                        <a href="{{ route('checkout.index') }}" class="py-2 px-3 text-center bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                            Thanh toán ngay
                        </a>
                    </div>
                </div>
            </div>
        </template>

        <!-- Khi giỏ rỗng -->
        <template x-if="!loading && (!cartData || cartData.items.length === 0)">
            <div class="p-6 text-center text-sm text-gray-500">
                <p class="mb-2">Giỏ hàng của bạn đang trống</p>
                <a href="{{ route('products.index') }}" class="text-xs text-red-600 font-bold hover:underline">Sắm máy ảnh ngay &rarr;</a>
            </div>
        </template>
    </div>
</div>
