<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
        <!-- 1. Hero Multi-Banner & Khối Cam Kết Chuẩn Kyma.vn -->
        @include('modules.Home.components.hero-banner')

        <!-- 2. Danh Mục Sản Phẩm Nổi Bật -->
        @php
            $featuredCategories = \App\Models\Category::withCount('products')->take(6)->get();
        @endphp
        @if ($featuredCategories->isNotEmpty())
            <div class="my-8">
                <div class="flex items-center justify-between mb-4 border-b-2 border-red-600 pb-2">
                    <h2 class="text-lg sm:text-xl font-black text-gray-900 uppercase tracking-tight flex items-center gap-2">
                        <span class="w-2.5 h-6 bg-red-600 inline-block rounded-sm"></span>
                        Danh Mục Nổi Bật
                    </h2>
                    <a href="{{ route('categories.index') }}" class="text-xs font-bold text-red-600 hover:underline">
                        Xem tất cả danh mục &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                    @foreach ($featuredCategories as $category)
                        <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
                           class="p-4 bg-white border border-gray-200 rounded-lg hover:border-red-500 hover:shadow-md transition text-center flex flex-col items-center group">
                            <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center text-xl mb-2 group-hover:scale-110 transition duration-300">
                                📷
                            </div>
                            <h3 class="font-bold text-gray-900 group-hover:text-red-600 transition text-xs sm:text-sm line-clamp-1">
                                {{ $category->name }}
                            </h3>
                            <span class="text-[11px] text-gray-400 mt-0.5">
                                {{ $category->products_count }} sản phẩm
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 3. Khu Vực FLASH SALE GIÁ SỐC (Đặc trưng thu hút của Kyma) -->
        @php
            $flashSaleProducts = \App\Models\Product::with(['productImages', 'category', 'brand'])
                ->where('price', '>', 0)
                ->inRandomOrder()
                ->take(4)
                ->get();
        @endphp
        @if ($flashSaleProducts->isNotEmpty())
            <div class="my-8 bg-gradient-to-r from-red-600 via-rose-600 to-red-700 p-4 sm:p-6 rounded-2xl shadow-lg">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 pb-2 border-b border-red-500/50 gap-2">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl animate-bounce">⚡</span>
                        <h2 class="text-xl sm:text-2xl font-black text-white uppercase tracking-tight">
                            GIỜ VÀNG GIÁ SỐC
                        </h2>
                        <span class="bg-black/30 text-white text-xs px-2.5 py-1 rounded font-mono font-bold">
                            KẾT THÚC TRONG: 12:45:00
                        </span>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-xs font-bold text-white hover:underline flex items-center gap-1">
                        Xem tất cả khuyến mãi &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($flashSaleProducts as $product)
                        @include('modules.Product.components.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 4. Khu Vực Máy Ảnh Mới Về (Layout Lưới chuẩn E-commerce) -->
        @php
            $latestProducts = \App\Models\Product::with(['productImages', 'category', 'brand'])
                ->latest()
                ->take(8)
                ->get();
        @endphp

        <div class="my-8">
            <div class="flex items-center justify-between mb-4 border-b-2 border-gray-900 pb-2">
                <h2 class="text-lg sm:text-xl font-black text-gray-900 uppercase tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-gray-900 inline-block rounded-sm"></span>
                    MÁY ẢNH MỚI NHẤT
                </h2>
                <div class="flex items-center gap-3 text-xs font-bold text-gray-500">
                    <a href="{{ route('products.index', ['brand' => 'Sony']) }}" class="hover:text-red-600 hidden sm:inline">Sony</a>
                    <a href="{{ route('products.index', ['brand' => 'Canon']) }}" class="hover:text-red-600 hidden sm:inline">Canon</a>
                    <a href="{{ route('products.index', ['brand' => 'Fujifilm']) }}" class="hover:text-red-600 hidden sm:inline">Fujifilm</a>
                    <a href="{{ route('products.index') }}" class="text-red-600 hover:underline">Xem thêm &rarr;</a>
                </div>
            </div>

            @if ($latestProducts->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($latestProducts as $product)
                        @include('modules.Product.components.product-card', ['product' => $product])
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg p-10 text-center border border-gray-200">
                    <p class="text-gray-500">Chưa có sản phẩm nào được cập nhật.</p>
                </div>
            @endif
        </div>

        <!-- 5. Kiến Thức & Tin Tức Nhiếp Ảnh (Giống Kyma.vn) -->
        <div class="my-10 bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-2">
                <h2 class="text-base sm:text-lg font-black text-gray-900 uppercase">
                    📸 GÓC NHIẾP ẢNH & KINH NGHIỆM
                </h2>
                <span class="text-xs text-red-600 font-bold cursor-pointer hover:underline">Xem tất cả bài viết</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="group cursor-pointer">
                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden mb-2">
                        <img src="https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=500&q=80" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <h3 class="font-bold text-xs sm:text-sm text-gray-900 group-hover:text-red-600 transition line-clamp-2">
                        Top 5 máy ảnh Mirrorless tốt nhất cho người mới bắt đầu năm 2026
                    </h3>
                    <p class="text-[11px] text-gray-400 mt-1">12 Tháng 10, 2026 • 5 phút đọc</p>
                </div>

                <div class="group cursor-pointer">
                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden mb-2">
                        <img src="https://images.unsplash.com/photo-1512790182412-b19e6d62bc39?auto=format&fit=crop&w=500&q=80" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <h3 class="font-bold text-xs sm:text-sm text-gray-900 group-hover:text-red-600 transition line-clamp-2">
                        Đánh giá chi tiết Sony Alpha 7 IV: Cỗ máy quay chụp hoàn hảo
                    </h3>
                    <p class="text-[11px] text-gray-400 mt-1">10 Tháng 10, 2026 • 8 phút đọc</p>
                </div>

                <div class="group cursor-pointer">
                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden mb-2">
                        <img src="https://images.unsplash.com/photo-1495121553079-4c61bcce189c?auto=format&fit=crop&w=500&q=80" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <h3 class="font-bold text-xs sm:text-sm text-gray-900 group-hover:text-red-600 transition line-clamp-2">
                        Hướng dẫn chọn ống kính chụp chân dung xóa phông chuẩn studio
                    </h3>
                    <p class="text-[11px] text-gray-400 mt-1">08 Tháng 10, 2026 • 6 phút đọc</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
