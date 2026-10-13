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
                <div class="flex items-center justify-between mb-4 border-b border-ink/10 pb-2">
                    <h2 class="font-heading text-lg sm:text-xl font-semibold text-ink uppercase tracking-tight flex items-center gap-2">
                        <span class="w-2.5 h-6 bg-primary inline-block rounded-sm"></span>
                        Danh Mục Nổi Bật
                    </h2>
                    <a href="{{ route('categories.index') }}" class="text-xs font-bold text-ink hover:underline">
                        Xem tất cả danh mục &rarr;
                    </a>
                </div>

                @php
                    $categoryImages = [
                        'dslr' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=150&q=80',
                        'mirrorless' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=150&q=80',
                        'compact' => 'https://images.unsplash.com/photo-1512790182412-b19e6d62bc39?auto=format&fit=crop&w=150&q=80',
                        'instant' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=150&q=80',
                        'action-camera' => 'https://images.unsplash.com/photo-1564466809058-bf4114d55352?auto=format&fit=crop&w=150&q=80',
                        'ong-kinh-lens' => 'https://images.unsplash.com/photo-1617005082133-548c4dd27f35?auto=format&fit=crop&w=150&q=80',
                        'phu-kien' => 'https://images.unsplash.com/photo-1584824486509-112e4181ff6b?auto=format&fit=crop&w=150&q=80',
                    ];
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                    @foreach ($featuredCategories as $category)
                        @php
                            $catSlug = Str::slug($category->name);
                            $catImg = $categoryImages[$catSlug] ?? 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=150&q=80';
                        @endphp
                        <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
                           class="p-3 bg-surface border border-ink/10 rounded-xl hover:border-primary hover:shadow-sm transition text-center flex flex-col items-center group">
                            <div class="w-16 h-16 rounded-xl bg-base p-1 mb-2 flex items-center justify-center overflow-hidden border border-ink/10 group-hover:scale-105 transition duration-300">
                                <img src="{{ $catImg }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-full h-full object-cover rounded-lg">
                            </div>
                            <h3 class="font-heading font-semibold tracking-tight text-ink group-hover:underline transition text-xs sm:text-sm line-clamp-1">
                                {{ $category->name }}
                            </h3>
                            <span class="text-xs text-ink/70 mt-0.5">
                                {{ $category->products_count }} sản phẩm
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 3. Khu Vực FLASH SALE GIÁ SỐC -->
        @php
            $flashSaleProducts = \App\Models\Product::with(['productImages', 'category', 'brand'])
                ->where('price', '>', 0)
                ->inRandomOrder()
                ->take(4)
                ->get();
        @endphp
        @if ($flashSaleProducts->isNotEmpty())
            <div class="my-8 bg-surface border border-ink/10 p-4 sm:p-6 rounded-2xl shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 pb-3 border-b border-ink/10 gap-2">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-accent text-ink">
                            <x-phosphor-lightning-light class="w-5 h-5 text-ink" />
                        </span>
                        <h2 class="font-heading text-xl sm:text-2xl font-semibold text-ink uppercase tracking-tight">
                            GIỜ VÀNG GIÁ SỐC
                        </h2>
                        <span class="bg-accent text-ink text-xs px-2.5 py-1 rounded-full font-mono font-bold tabular-nums shadow-xs">
                            KẾT THÚC TRONG: 12:45:00
                        </span>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-xs font-bold text-ink hover:underline flex items-center gap-1">
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
            <div class="flex items-center justify-between mb-4 border-b border-ink/10 pb-2">
                <h2 class="font-heading text-lg sm:text-xl font-semibold text-ink uppercase tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-ink inline-block rounded-sm"></span>
                    MÁY ẢNH MỚI NHẤT
                </h2>
                <div class="flex items-center gap-3 text-xs font-bold text-ink/70">
                    <a href="{{ route('products.index', ['brand' => 'Sony']) }}" class="hover:text-ink hidden sm:inline">Sony</a>
                    <a href="{{ route('products.index', ['brand' => 'Canon']) }}" class="hover:text-ink hidden sm:inline">Canon</a>
                    <a href="{{ route('products.index', ['brand' => 'Fujifilm']) }}" class="hover:text-ink hidden sm:inline">Fujifilm</a>
                    <a href="{{ route('products.index') }}" class="text-ink hover:underline">Xem thêm &rarr;</a>
                </div>
            </div>

            @if ($latestProducts->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($latestProducts as $product)
                        @include('modules.Product.components.product-card', ['product' => $product])
                    @endforeach
                </div>
            @else
                <div class="bg-surface rounded-xl p-10 text-center border border-ink/10">
                    <p class="text-sm text-ink/70">Chưa có sản phẩm nào được cập nhật.</p>
                </div>
            @endif
        </div>

        <!-- 5. Kiến Thức & Tin Tức Nhiếp Ảnh -->
        <div class="my-10 bg-surface border border-ink/10 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 border-b border-ink/10 pb-2">
                <h2 class="font-heading text-base sm:text-lg font-semibold tracking-tight text-ink uppercase flex items-center gap-2">
                    <x-phosphor-film-strip-light class="w-5 h-5 text-ink" />
                    GÓC NHIẾP ẢNH & KINH NGHIỆM
                </h2>
                <span class="text-xs text-ink font-bold cursor-pointer hover:underline">Xem tất cả bài viết</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="group cursor-pointer">
                    <div class="aspect-video bg-base rounded-lg overflow-hidden mb-2 border border-ink/10">
                        <img src="https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=500&q=80" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <h3 class="font-heading font-semibold tracking-tight text-xs sm:text-sm text-ink group-hover:underline transition line-clamp-2">
                        Top 5 máy ảnh Mirrorless tốt nhất cho người mới bắt đầu năm 2026
                    </h3>
                    <p class="text-xs text-ink/70 mt-1">12 Tháng 10, 2026 • 5 phút đọc</p>
                </div>

                <div class="group cursor-pointer">
                    <div class="aspect-video bg-base rounded-lg overflow-hidden mb-2 border border-ink/10">
                        <img src="https://images.unsplash.com/photo-1512790182412-b19e6d62bc39?auto=format&fit=crop&w=500&q=80" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <h3 class="font-heading font-semibold tracking-tight text-xs sm:text-sm text-ink group-hover:underline transition line-clamp-2">
                        Đánh giá chi tiết Sony Alpha 7 IV: Cỗ máy quay chụp hoàn hảo
                    </h3>
                    <p class="text-xs text-ink/70 mt-1">10 Tháng 10, 2026 • 8 phút đọc</p>
                </div>

                <div class="group cursor-pointer">
                    <div class="aspect-video bg-base rounded-lg overflow-hidden mb-2 border border-ink/10">
                        <img src="https://images.unsplash.com/photo-1495121553079-4c61bcce189c?auto=format&fit=crop&w=500&q=80" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <h3 class="font-heading font-semibold tracking-tight text-xs sm:text-sm text-ink group-hover:underline transition line-clamp-2">
                        Hướng dẫn chọn ống kính chụp chân dung xóa phông chuẩn studio
                    </h3>
                    <p class="text-xs text-ink/70 mt-1">08 Tháng 10, 2026 • 6 phút đọc</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
