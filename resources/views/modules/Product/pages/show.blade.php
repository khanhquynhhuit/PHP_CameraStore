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
        <nav class="flex text-xs text-gray-500 mb-6 gap-2 items-center">
            <a href="{{ route('home') }}" class="hover:text-red-600">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-red-600">Sản phẩm</a>
            <span>/</span>
            <span class="text-gray-900 font-bold truncate">{{ $product->name }}</span>
        </nav>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Cột trái: Ảnh sản phẩm -->
                <div>
                    <div class="relative aspect-square rounded-xl border border-gray-200 overflow-hidden bg-white p-6 flex items-center justify-center">
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $product->name }}" 
                             class="max-w-full max-h-full object-contain"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80';">
                        
                        @if ($discountPercent > 0)
                            <span class="absolute top-4 left-4 px-3 py-1 bg-red-600 text-white font-black text-xs rounded-md shadow-sm">
                                GIẢM {{ $discountPercent }}%
                            </span>
                        @endif

                        <div class="absolute top-4 right-4">
                            @if ($isOutOfStock)
                                <span class="px-3 py-1 bg-red-100 text-red-700 font-bold text-xs rounded-full border border-red-200">
                                    🔴 HẾT HÀNG
                                </span>
                            @else
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-full border border-emerald-200">
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
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded font-bold uppercase">
                                {{ $product->brand?->name ?? 'CHÍNH HÃNG' }}
                            </span>
                            <span class="text-gray-400">Mã SKU: {{ $product->sku ?? 'N/A' }}</span>
                            <span class="text-amber-500 font-bold">★ 5.0 (Đánh giá tốt)</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight">
                            {{ $product->name }}
                        </h1>

                        <!-- Khối giá -->
                        <div class="mt-4 p-4 bg-red-50/60 rounded-xl border border-red-100 flex items-baseline gap-4">
                            <span class="text-3xl font-black text-red-600">
                                {{ number_format($product->price, 0, ',', '.') }} đ
                            </span>
                            @if ($product->original_price && $product->original_price > $product->price)
                                <span class="text-base text-gray-400 line-through">
                                    {{ number_format($product->original_price, 0, ',', '.') }} đ
                                </span>
                            @endif
                            <span class="ms-auto text-xs font-bold text-red-700 bg-red-200/60 px-2 py-0.5 rounded">
                                Trả góp 0%
                            </span>
                        </div>

                        <!-- Ưu đãi tặng kèm giống Kyma -->
                        <div class="mt-5 p-4 rounded-xl border border-dashed border-red-300 bg-white text-xs space-y-2">
                            <p class="font-bold text-red-600 flex items-center gap-1.5 text-sm">
                                <span>🎁</span> QUÀ TẶNG KHUYẾN MÃI KÈM THEO:
                            </p>
                            <p class="text-gray-600">• Tặng thẻ nhớ SanDisk Extreme Pro 64GB 200MB/s trị giá 450.000đ</p>
                            <p class="text-gray-600">• Tặng túi máy ảnh chuyên dụng chống nước cao cấp</p>
                            <p class="text-gray-600">• Dán màn hình cường lực & vệ sinh máy ảnh trọn đời</p>
                        </div>

                        <!-- Tóm tắt thông số kỹ thuật -->
                        @if ($product->description)
                            <div class="mt-5">
                                <h3 class="text-xs font-bold uppercase text-gray-400 tracking-wider mb-2">Mô tả sản phẩm</h3>
                                <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">
                                    {{ $product->description }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- KHỐI HÀNH ĐỘNG: KIỂM TRA HẾT HÀNG / CÒN HÀNG -->
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        @if ($isOutOfStock)
                            <!-- TRƯỜNG HỢP HẾT HÀNG: CẢNH BÁO ĐỎ & KHÔNG CHO MUA -->
                            <div class="p-4 bg-red-50 border border-red-200 rounded-xl mb-4">
                                <div class="flex items-center gap-2 text-red-700 font-bold text-base mb-1">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    SẢN PHẨM HIỆN ĐANG HẾT HÀNG
                                </div>
                                <p class="text-xs text-red-600">
                                    Rất tiếc, sản phẩm này hiện đã hết hàng trong kho. Quý khách vui lòng liên hệ hotline <strong class="underline">1900 6789</strong> để được thông báo khi có hàng lại.
                                </p>
                            </div>

                            <button disabled 
                                    class="w-full py-4 bg-gray-300 text-gray-500 font-black rounded-xl text-base cursor-not-allowed uppercase tracking-wider flex items-center justify-center gap-2 shadow-inner">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                TẠM HẾT HÀNG (KHÔNG THỂ ĐẶT MUA)
                            </button>
                        @else
                            <!-- TRƯỜNG HỢP CÒN HÀNG: CHO PHÉP CHỌN SỐ LƯỢNG VÀ THÊM VÀO GIỎ -->
                            <form method="POST" action="{{ route('cart.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-bold text-gray-700">Số lượng:</span>
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-gray-50">
                                        <button type="button" 
                                                onclick="const q = document.getElementById('qty-input'); if(q.value > 1) q.value--;" 
                                                class="px-3 py-1.5 text-gray-600 hover:bg-gray-200 font-bold">-</button>
                                        <input type="number" 
                                               id="qty-input" 
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $product->stock }}" 
                                               class="w-14 text-center border-0 bg-transparent text-sm font-bold focus:ring-0 py-1.5">
                                        <button type="button" 
                                                onclick="const q = document.getElementById('qty-input'); if(q.value < {{ $product->stock }}) q.value++;" 
                                                class="px-3 py-1.5 text-gray-600 hover:bg-gray-200 font-bold">+</button>
                                    </div>
                                    <span class="text-xs text-gray-400">Còn lại {{ $product->stock }} máy trong kho</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                    <button type="submit" 
                                            class="w-full py-3.5 px-4 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl text-sm transition shadow-lg shadow-red-600/30 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.24-1.2A1 1 0 005.06 1H3z"/></svg>
                                        THÊM VÀO GIỎ HÀNG
                                    </button>

                                    <button type="submit" 
                                            class="w-full py-3.5 px-4 bg-gray-900 hover:bg-black text-white font-black rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                                        MUA NGAY (GIAO TẬN NƠI)
                                    </button>
                                </div>
                            </form>
                        @endif

                        <!-- Cam kết bảo hành -->
                        <div class="mt-6 grid grid-cols-3 gap-2 text-center text-[11px] text-gray-500 pt-4 border-t border-gray-100">
                            <div>
                                <p class="font-bold text-gray-800">✅ 100% Chính hãng</p>
                                <p>Đầy đủ VAT & CO/CQ</p>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">🔄 Đổi trả 7 ngày</p>
                                <p>Lỗi nhà sản xuất</p>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">🚚 Giao hàng 2H</p>
                                <p>Nội thành siêu tốc</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
