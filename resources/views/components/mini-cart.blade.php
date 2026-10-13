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
       class="relative flex items-center gap-2 p-2 px-3 text-ink bg-surface hover:bg-sand/60 focus:outline-none transition rounded-lg border border-beige/60"
       title="Giỏ hàng">
        <div class="relative">
            <x-phosphor-shopping-cart-light class="w-6 h-6 text-primary" />
            @if ($cartItemCount > 0)
                <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-1.5 py-0.5 text-[11px] font-bold text-cream bg-primary rounded-full border-2 border-surface shadow-sm">
                    {{ $cartItemCount > 99 ? '99+' : $cartItemCount }}
                </span>
            @endif
        </div>
        <div class="hidden lg:block text-left text-xs">
            <span class="text-ink/65 block text-[10px] uppercase font-bold">Giỏ hàng</span>
            <span class="font-bold text-ink">
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
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-surface text-ink rounded-xl shadow-xl border border-beige/60 z-50 overflow-hidden"
         style="display: none;">
        
        <div class="p-3 bg-base border-b border-beige/40 flex items-center justify-between">
            <span class="text-sm font-bold text-ink flex items-center gap-1.5">
                <x-phosphor-shopping-cart-light class="w-4 h-4 text-primary" />
                Giỏ hàng của bạn ({{ $cartItemCount }} món)
            </span>
            <a href="{{ route('cart.index') }}" class="text-xs text-primary-dark hover:underline font-bold">Xem giỏ hàng &rarr;</a>
        </div>

        @if ($cartItems->isNotEmpty())
            <div>
                <div class="max-h-64 overflow-y-auto divide-y divide-beige/30 px-3">
                    @foreach ($cartItems as $item)
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-11 h-11 rounded border border-beige/40 bg-base shrink-0 flex items-center justify-center overflow-hidden">
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
                                    <a href="{{ route('products.show', $item->product->id) }}" class="text-xs font-semibold text-ink truncate block hover:underline">
                                        {{ $item->product->name }}
                                    </a>
                                    <p class="text-xs text-ink/70 mt-0.5">
                                        <span class="font-bold text-ink">{{ $item->quantity }}</span> x 
                                        <span class="font-heading font-bold tabular-nums text-primary">{{ number_format($item->product->price, 0, ',', '.') }} đ</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-3 bg-base border-t border-beige/40">
                    <div class="flex justify-between items-center text-sm mb-3">
                        <span class="text-ink/70 font-medium">Tổng tiền:</span>
                        <span class="font-heading text-base font-bold tabular-nums text-primary">{{ number_format($cartSubtotal, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <x-button variant="outline" size="sm" :href="route('cart.index')" class="w-full">
                            Xem giỏ
                        </x-button>
                        <x-button variant="primary" size="sm" :href="route('checkout.index')" class="w-full">
                            Thanh toán ngay
                        </x-button>
                    </div>
                </div>
            </div>
        @else
            <!-- Khi giỏ rỗng -->
            <div class="p-6 text-center text-sm text-ink/70">
                <x-phosphor-shopping-bag-light class="w-10 h-10 text-stone/60 mx-auto mb-2" />
                <p class="mb-2">Giỏ hàng của bạn đang trống</p>
                <a href="{{ route('products.index') }}" class="text-xs text-primary-dark font-bold hover:underline">Sắm máy ảnh ngay &rarr;</a>
            </div>
        @endif
    </div>
</div>
