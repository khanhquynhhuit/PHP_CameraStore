@props(['product'])

@php
    $image = $product->primary_image;
    $imageUrl = $image ? asset('storage/' . $image->image_url) : 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80';
    $isOutOfStock = ($product->stock ?? 0) <= 0;
    $discountPercent = 0;
    if ($product->original_price && $product->original_price > $product->price) {
        $discountPercent = round((($product->original_price - $product->price) / $product->original_price) * 100);
    }
@endphp

<div class="group relative bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-xl hover:border-red-500 transition-all duration-300 flex flex-col justify-between">
    <div>
        <!-- Ảnh Sản Phẩm & Nhãn Khuyến Mãi (Phong cách Kyma) -->
        <div class="relative w-full aspect-square bg-white p-4 flex items-center justify-center overflow-hidden border-b border-gray-100">
            <a href="{{ route('products.show', $product->id) }}" class="w-full h-full flex items-center justify-center">
                <img src="{{ $imageUrl }}" 
                     alt="{{ $product->name }}" 
                     class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300"
                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80';">
            </a>

            <!-- Badge giảm giá góc trái giống Kyma -->
            <div class="absolute top-2 left-2 flex flex-col gap-1 items-start">
                @if ($discountPercent > 0)
                    <span class="px-2 py-0.5 text-[11px] font-black text-white bg-red-600 rounded">
                        -{{ $discountPercent }}%
                    </span>
                @endif
                <span class="px-1.5 py-0.5 text-[10px] font-bold text-red-700 bg-red-100 rounded">
                    Trả góp 0%
                </span>
            </div>

            <!-- Tình trạng kho hàng góc phải -->
            <div class="absolute top-2 right-2">
                @if ($isOutOfStock)
                    <span class="px-2 py-0.5 text-[10px] font-bold text-gray-600 bg-gray-200 rounded">
                        Tạm hết
                    </span>
                @else
                    <span class="px-2 py-0.5 text-[10px] font-bold text-emerald-700 bg-emerald-50 rounded border border-emerald-200">
                        Sẵn hàng
                    </span>
                @endif
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="p-3.5">
            <!-- Thương hiệu / Danh mục -->
            <div class="flex items-center justify-between text-[11px] text-gray-500 mb-1">
                <span class="uppercase font-semibold tracking-wider text-gray-400">
                    {{ $product->brand?->name ?? 'CHÍNH HÃNG' }}
                </span>
                <span class="text-amber-500 flex items-center gap-0.5">
                    ★ 5.0 ({{ rand(5, 45) }})
                </span>
            </div>

            <!-- Tên sản phẩm -->
            <h3 class="font-bold text-gray-900 group-hover:text-red-600 transition-colors text-sm line-clamp-2 min-h-[40px] leading-snug">
                <a href="{{ route('products.show', $product->id) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Giá bán nổi bật đỏ đậm đặc trưng Kyma -->
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-base font-black text-red-600">
                    {{ number_format($product->price, 0, ',', '.') }} đ
                </span>
                @if ($product->original_price && $product->original_price > $product->price)
                    <span class="text-xs text-gray-400 line-through">
                        {{ number_format($product->original_price, 0, ',', '.') }} đ
                    </span>
                @endif
            </div>

            <!-- Quà tặng kèm / Ưu đãi nhỏ giống Kyma -->
            <div class="mt-2 pt-2 border-t border-dashed border-gray-200 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="text-red-500 font-bold">🎁</span>
                <span class="truncate">Tặng thẻ nhớ SD 64GB + Túi máy ảnh</span>
            </div>
        </div>
    </div>

    <!-- Nút Mua hàng & Thêm giỏ hàng -->
    <div class="p-3 pt-0 grid grid-cols-2 gap-2">
        <a href="{{ route('products.show', $product->id) }}" 
           class="text-center py-2 px-2 border border-gray-300 hover:border-gray-400 text-gray-700 text-xs font-bold rounded transition">
            Xem chi tiết
        </a>

        @if (!$isOutOfStock)
            <form method="POST" action="{{ route('cart.store') }}" class="w-full">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" 
                        class="w-full py-2 px-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded transition flex items-center justify-center gap-1 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.24-1.2A1 1 0 005.06 1H3z"/></svg>
                    <span>Thêm giỏ</span>
                </button>
            </form>
        @else
            <button disabled 
                    class="w-full py-2 px-2 bg-gray-200 text-gray-400 text-xs font-bold rounded cursor-not-allowed">
                Hết hàng
            </button>
        @endif
    </div>
</div>
