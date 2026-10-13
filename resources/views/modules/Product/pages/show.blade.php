<x-app-layout>
    @php
        $image = $product->primary_image;
        $imageUrl = $image ? asset('storage/' . $image->image_url) : 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80';
        $isOutOfStock = ($product->stock ?? 0) <= 0;
        $discountPercent = 0;
        if ($product->original_price && $product->original_price > $product->price) {
            $discountPercent = round((($product->original_price - $product->price) / $product->original_price) * 100);
        }
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <nav class="flex text-xs text-ink/70 mb-6 gap-2 items-center">
            <a href="{{ route('home') }}" class="hover:text-ink hover:underline">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-ink hover:underline">Sản phẩm</a>
            <span>/</span>
            <span class="text-ink font-bold truncate">{{ $product->name }}</span>
        </nav>

        <div class="bg-surface rounded-2xl border border-ink/10 p-6 sm:p-8 shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Cột trái: Ảnh sản phẩm (Nền sáng nổi bật máy ảnh) -->
                <div>
                    <div class="relative aspect-square rounded-xl border border-ink/10 overflow-hidden bg-base p-6 flex items-center justify-center">
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $product->name }}" 
                             class="max-w-full max-h-full object-contain"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80';">
                        
                        @if ($discountPercent > 0)
                            <span class="absolute top-4 left-4 px-3 py-1 bg-accent text-ink font-semibold text-xs rounded-full shadow-xs">
                                GIẢM {{ $discountPercent }}%
                            </span>
                        @endif

                        <div class="absolute top-4 right-4">
                            @if ($isOutOfStock)
                                <span class="px-3 py-1 bg-danger text-danger-ink font-semibold text-xs rounded-full">
                                    🔴 HẾT HÀNG
                                </span>
                            @else
                                <span class="px-3 py-1 bg-success text-success-ink font-semibold text-xs rounded-full">
                                    🟢 CÒN HÀNG ({{ $product->stock }} chiếc)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Cột phải: Thông tin & Nút mua hàng / Hết hàng -->
                <div class="flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 text-xs mb-2">
                            <span class="px-2.5 py-1 bg-base border border-ink/10 text-ink rounded font-bold uppercase">
                                {{ $product->brand?->name ?? 'CHÍNH HÃNG' }}
                            </span>
                            <span class="text-ink/70">Mã SKU: {{ $product->sku ?? 'N/A' }}</span>
                            <span class="text-warning-ink font-bold">★ 5.0 (Đánh giá tốt)</span>
                        </div>

                        <h1 class="font-heading text-2xl sm:text-3xl font-semibold tracking-tight text-ink leading-tight">
                            {{ $product->name }}
                        </h1>

                        <!-- Khối giá -->
                        <div class="mt-4 p-4 bg-base rounded-xl border border-ink/10 flex items-baseline gap-4">
                            <span class="font-heading text-3xl font-bold tabular-nums text-primary">
                                {{ number_format($product->price, 0, ',', '.') }} đ
                            </span>
                            @if ($product->original_price && $product->original_price > $product->price)
                                <span class="font-heading text-base font-bold tabular-nums text-ink/50 line-through">
                                    {{ number_format($product->original_price, 0, ',', '.') }} đ
                                </span>
                            @endif
                            <span class="ms-auto text-xs font-semibold text-ink bg-accent px-3 py-1 rounded-full">
                                Trả góp 0%
                            </span>
                        </div>

                        <!-- Ưu đãi tặng kèm -->
                        <div class="mt-5 p-4 rounded-xl border border-dashed border-ink/20 bg-base text-xs space-y-2">
                            <p class="font-heading font-semibold text-ink flex items-center gap-1.5 text-sm">
                                <x-phosphor-gift-light class="w-4 h-4 text-ink inline-block" /> QUÀ TẶNG KHUYẾN MÃI KÈM THEO:
                            </p>
                            <p class="text-sm text-ink/70">• Tặng thẻ nhớ SanDisk Extreme Pro 64GB 200MB/s trị giá 450.000đ</p>
                            <p class="text-sm text-ink/70">• Tặng túi máy ảnh chuyên dụng chống nước cao cấp</p>
                            <p class="text-sm text-ink/70">• Dán màn hình cường lực & vệ sinh máy ảnh trọn đời</p>
                        </div>

                        <!-- Tóm tắt thông số kỹ thuật -->
                        @if ($product->description)
                            <div class="mt-5">
                                <h3 class="font-heading text-xs font-semibold uppercase text-ink/70 tracking-tight mb-2">Mô tả sản phẩm</h3>
                                <p class="text-sm text-ink/70 leading-relaxed line-clamp-3">
                                    {{ $product->description }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- KHỐI HÀNH ĐỘNG: KIỂM TRA HẾT HÀNG / CÒN HÀNG -->
                    <div class="mt-8 pt-6 border-t border-ink/10">
                        @if ($isOutOfStock)
                            <!-- TRƯỜNG HỢP HẾT HÀNG: CẢNH BÁO ĐỎ & KHÔNG CHO MUA -->
                            <div class="p-4 bg-danger border border-danger-ink/20 rounded-xl mb-4">
                                <div class="flex items-center gap-2 text-danger-ink font-heading font-semibold text-base mb-1">
                                    <svg class="w-5 h-5 text-danger-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    SẢN PHẨM HIỆN ĐANG HẾT HÀNG
                                </div>
                                <p class="text-xs text-danger-ink">
                                    Rất tiếc, sản phẩm này hiện đã hết hàng trong kho. Quý khách vui lòng liên hệ hotline <strong class="underline">1900 6789</strong> để được thông báo khi có hàng lại.
                                </p>
                            </div>

                            <x-button variant="secondary" size="lg" disabled class="w-full uppercase tracking-wider">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                TẠM HẾT HÀNG (KHÔNG THỂ ĐẶT MUA)
                            </x-button>
                        @else
                            <!-- TRƯỜNG HỢP CÒN HÀNG: CHO PHÉP CHỌN SỐ LƯỢNG VÀ THÊM VÀO GIỎ -->
                            <form method="POST" action="{{ route('cart.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-bold text-ink">Số lượng:</span>
                                    <div class="flex items-center border border-ink/20 rounded-lg overflow-hidden bg-base">
                                        <button type="button" 
                                                onclick="const q = document.getElementById('qty-input'); if(q.value > 1) q.value--;" 
                                                class="px-3 py-1.5 text-ink hover:bg-surface font-bold">-</button>
                                        <input type="number" 
                                               id="qty-input" 
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $product->stock }}" 
                                               class="w-14 text-center border-0 bg-transparent text-ink text-sm font-bold tabular-nums focus:ring-0 py-1.5">
                                        <button type="button" 
                                                onclick="const q = document.getElementById('qty-input'); if(q.value < {{ $product->stock }}) q.value++;" 
                                                class="px-3 py-1.5 text-ink hover:bg-surface font-bold">+</button>
                                    </div>
                                    <span class="text-sm text-ink/70">Còn lại {{ $product->stock }} máy trong kho</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                    <x-button variant="primary" size="lg" type="submit" class="w-full">
                                        <x-phosphor-shopping-cart-light class="w-5 h-5" />
                                        THÊM VÀO GIỎ HÀNG
                                    </x-button>

                                    <x-button variant="outline" size="lg" type="submit" class="w-full">
                                        MUA NGAY (GIAO TẬN NƠI)
                                    </x-button>
                                </div>
                            </form>
                        @endif

                        <!-- Cam kết bảo hành -->
                        <div class="mt-6 grid grid-cols-3 gap-2 text-center text-xs text-ink/70 pt-4 border-t border-ink/10">
                            <div>
                                <p class="font-bold text-ink flex items-center justify-center gap-1">
                                    <x-phosphor-seal-check-light class="w-4 h-4 text-ink" />
                                    100% Chính hãng
                                </p>
                                <p>Đầy đủ VAT & CO/CQ</p>
                            </div>
                            <div>
                                <p class="font-bold text-ink flex items-center justify-center gap-1">
                                    <x-phosphor-arrows-clockwise-light class="w-4 h-4 text-ink" />
                                    Đổi trả 7 ngày
                                </p>
                                <p>Lỗi nhà sản xuất</p>
                            </div>
                            <div>
                                <p class="font-bold text-ink flex items-center justify-center gap-1">
                                    <x-phosphor-truck-light class="w-4 h-4 text-ink" />
                                    Giao hàng 2H
                                </p>
                                <p>Nội thành siêu tốc</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
