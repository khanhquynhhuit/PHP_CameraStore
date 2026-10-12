@props(['product'])

@php
    $image = $product->primary_image;
    $imageUrl = $image ? asset('storage/' . $image->image_url) : 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80';
    $isOutOfStock = ($product->stock ?? 0) <= 0;
@endphp

<div class="group relative bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
    <div>
        <!-- Ảnh Sản Phẩm & Badge -->
        <div class="relative w-full aspect-square bg-gray-50 overflow-hidden flex items-center justify-center">
            <img src="{{ $imageUrl }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                 onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80';">
            
            <!-- Badge Tình trạng -->
            <div class="absolute top-3 left-3 flex flex-col gap-1">
                @if ($isOutOfStock)
                    <span class="px-2.5 py-1 text-xs font-bold text-white bg-red-600 rounded-full shadow-sm">
                        Hết hàng
                    </span>
                @else
                    <span class="px-2.5 py-1 text-xs font-semibold text-emerald-800 bg-emerald-100/90 backdrop-blur-sm rounded-full shadow-sm">
                        Còn {{ $product->stock }} máy
                    </span>
                @endif

                @if ($product->original_price && $product->original_price > $product->price)
                    @php
                        $discountPercent = round((($product->original_price - $product->price) / $product->original_price) * 100);
                    @endphp
                    <span class="px-2 py-0.5 text-xs font-bold text-white bg-amber-500 rounded-full shadow-sm">
                        -{{ $discountPercent }}%
                    </span>
                @endif
            </div>

            <!-- Tên danh mục nhỏ -->
            @if ($product->category)
                <span class="absolute bottom-2 left-3 text-[11px] font-medium text-gray-500 bg-white/80 backdrop-blur-sm px-2 py-0.5 rounded">
                    {{ $product->category->name }}
                </span>
            @endif
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="p-4 sm:p-5">
            <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors text-sm sm:text-base line-clamp-2 min-h-[44px]">
                <a href="{{ route('products.show', $product->id) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Giá bán -->
            <div class="mt-2.5 flex items-baseline gap-2">
                <span class="text-base sm:text-lg font-black text-indigo-600">
                    {{ number_format($product->price, 0, ',', '.') }} đ
                </span>
                @if ($product->original_price && $product->original_price > $product->price)
                    <span class="text-xs text-gray-400 line-through">
                        {{ number_format($product->original_price, 0, ',', '.') }} đ
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Nút hành động -->
    <div class="p-4 sm:p-5 pt-0 grid grid-cols-2 gap-2">
        <a href="{{ route('products.show', $product->id) }}" 
           class="w-full text-center py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs sm:text-sm font-semibold rounded-xl transition">
            Chi tiết
        </a>

        @if (!$isOutOfStock)
            <form method="POST" action="{{ route('cart.store') }}" class="w-full">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" 
                        class="w-full py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition flex items-center justify-center gap-1 shadow-sm shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Thêm giỏ</span>
                </button>
            </form>
        @else
            <button disabled 
                    class="w-full py-2 px-3 bg-gray-200 text-gray-400 text-xs sm:text-sm font-semibold rounded-xl cursor-not-allowed">
                Tạm hết
            </button>
        @endif
    </div>
</div>
