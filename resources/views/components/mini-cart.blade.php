@php
    $cartItems = collect();
    $cartSubtotal = 0;
    $cartItemCount = 0;

    if (Auth::check()) {
        $userCart = \App\Models\Cart::where('user_id', Auth::id())
            ->with(['items.product.productImages'])
            ->first();

        if ($userCart) {
            $cartItems = $userCart->items;
            $cartItemCount = $cartItems->sum('quantity');
            $cartSubtotal = $cartItems->sum(function ($item) {
                return (float) $item->product->price * $item->quantity;
            });
        }
    }
@endphp

<div x-data="{ open: false }" 
     @click.away="open = false" 
     class="relative">
    <!-- Trigger Button -->
    <a href="{{ route('cart.index') }}" 
       @mouseenter="open = true"
       class="relative flex items-center gap-2 p-2 px-3 text-gray-700 hover:text-red-600 focus:outline-none transition rounded-lg hover:bg-red-50 border border-gray-200"
       title="Giỏ hàng">
        <div class="relative">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            @if ($cartItemCount > 0)
                <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-1.5 py-0.5 text-[11px] font-bold text-white bg-red-600 rounded-full border-2 border-white shadow-sm">
                    {{ $cartItemCount > 99 ? '99+' : $cartItemCount }}
                </span>
            @endif
        </div>
        <div class="hidden lg:block text-left text-xs">
            <span class="text-gray-400 block text-[10px] uppercase font-bold">Giỏ hàng</span>
            <span class="font-bold text-gray-800">
                {{ $cartItemCount }} món
            </span>
        </div>
    </a>

    <!-- Dropdown Panel (Hiển thị ngay lập tức từ Server) -->
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
                Giỏ hàng của bạn ({{ $cartItemCount }} món)
            </span>
            <a href="{{ route('cart.index') }}" class="text-xs text-red-600 hover:underline font-bold">Xem giỏ hàng &rarr;</a>
        </div>

        @if ($cartItems->isNotEmpty())
            <div>
                <div class="max-h-64 overflow-y-auto divide-y divide-gray-100 px-3">
                    @foreach ($cartItems as $item)
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-11 h-11 rounded border border-gray-100 bg-gray-50 shrink-0 flex items-center justify-center overflow-hidden">
                                    @php
                                        $image = $item->product->primary_image;
                                        $imgUrl = $image ? asset('storage/' . $image->image_url) : 'https://placehold.co/100x100?text=Camera';
                                    @endphp
                                    <img src="{{ $imgUrl }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Camera';">
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('products.show', $item->product->id) }}" class="text-xs font-semibold text-gray-900 truncate block hover:text-red-600">
                                        {{ $item->product->name }}
                                    </a>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        <span class="font-bold text-gray-700">{{ $item->quantity }}</span> x 
                                        <span class="text-red-600 font-bold">{{ number_format($item->product->price, 0, ',', '.') }} đ</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-3 bg-gray-50 border-t border-gray-100">
                    <div class="flex justify-between items-center text-sm mb-3">
                        <span class="text-gray-600 font-medium">Tổng tiền:</span>
                        <span class="text-base font-black text-red-600">{{ number_format($cartSubtotal, 0, ',', '.') }} đ</span>
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
        @else
            <!-- Khi giỏ rỗng -->
            <div class="p-6 text-center text-sm text-gray-500">
                <p class="mb-2">Giỏ hàng của bạn đang trống</p>
                <a href="{{ route('products.index') }}" class="text-xs text-red-600 font-bold hover:underline">Sắm máy ảnh ngay &rarr;</a>
            </div>
        @endif
    </div>
</div>
