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

<div class="group relative bg-surface border border-ink/10 rounded-xl shadow-sm overflow-hidden hover:shadow-md hover:border-primary transition-all duration-300 flex flex-col justify-between">
    <div>
        <!-- Ảnh Sản Phẩm & Nhãn Khuyến Mãi (Nền sáng nổi bật ảnh sản phẩm) -->
        <div class="relative w-full aspect-square bg-base p-4 flex items-center justify-center overflow-hidden border-b border-ink/10">
            <a href="{{ route('products.show', $product->id) }}" class="w-full h-full flex items-center justify-center">
                <img src="{{ $imageUrl }}" 
                     alt="{{ $product->name }}" 
                     class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300"
                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80';">
            </a>

            <!-- Badge giảm giá góc trái dùng màu Accent -->
            <div class="absolute top-2 left-2 flex flex-col gap-1 items-start">
                @if ($discountPercent > 0)
                    <span class="px-2.5 py-0.5 text-xs font-semibold text-ink bg-accent rounded-full shadow-xs">
                        -{{ $discountPercent }}%
                    </span>
                @endif
                <span class="px-2 py-0.5 text-[10px] font-bold text-primary-dark bg-sand rounded-full">
                    Trả góp 0%
                </span>
            </div>

            <!-- Tình trạng kho hàng góc phải -->
            <div class="absolute top-2 right-2">
                @if ($isOutOfStock)
                    <span class="px-2.5 py-0.5 text-[10px] font-semibold text-danger-ink bg-danger rounded-full">
                        Tạm hết
                    </span>
                @else
                    <span class="px-2.5 py-0.5 text-[10px] font-semibold text-success-ink bg-success rounded-full">
                        Sẵn hàng
                    </span>
                @endif
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="p-4">
            <!-- Thương hiệu / Danh mục -->
            <div class="flex items-center justify-between text-[11px] text-ink/70 mb-1">
                <span class="uppercase font-semibold tracking-wider text-ink/60">
                    {{ $product->brand?->name ?? 'CHÍNH HÃNG' }}
                </span>
                <span class="text-warning-ink font-semibold flex items-center gap-0.5">
                    <x-phosphor-star-fill class="w-3.5 h-3.5 text-warning-ink" />
                    <span>5.0 ({{ rand(5, 45) }})</span>
                </span>
            </div>

            <!-- Tên sản phẩm -->
            <h3 class="font-heading font-semibold tracking-tight text-ink group-hover:underline transition-colors text-sm line-clamp-2 min-h-[40px] leading-snug">
                <a href="{{ route('products.show', $product->id) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Giá bán nổi bật theo chuẩn màu Primary (Castleton Green) -->
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-heading text-base font-bold tabular-nums text-primary">
                    {{ number_format($product->price, 0, ',', '.') }} đ
                </span>
                @if ($product->original_price && $product->original_price > $product->price)
                    <span class="font-heading text-xs font-bold tabular-nums text-ink/50 line-through">
                        {{ number_format($product->original_price, 0, ',', '.') }} đ
                    </span>
                @endif
            </div>

            <!-- Quà tặng kèm / Ưu đãi nhỏ -->
            <div class="mt-2 pt-2 border-t border-dashed border-ink/10 text-xs text-ink/70 flex items-center gap-1.5">
                <x-phosphor-gift-light class="w-3.5 h-3.5 text-ink shrink-0" />
                <span class="truncate">Tặng thẻ nhớ SD 64GB + Túi máy ảnh</span>
            </div>
        </div>
    </div>

    <!-- Nút Mua hàng & Thêm giỏ hàng -->
    <div class="p-3 pt-0 grid grid-cols-2 gap-2">
        <x-button variant="outline" size="sm" :href="route('products.show', $product->id)" class="w-full px-2">
            Xem chi tiết
        </x-button>

        @if (!$isOutOfStock)
            <form method="POST" action="{{ route('cart.store') }}" class="w-full">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <x-button variant="primary" size="sm" type="submit" class="w-full px-2">
                    <x-phosphor-shopping-cart-light class="w-3.5 h-3.5" />
                    <span>Thêm giỏ</span>
                </x-button>
            </form>
        @else
            <x-button variant="secondary" size="sm" disabled class="w-full px-2">
                Hết hàng
            </x-button>
        @endif
    </div>
</div>
