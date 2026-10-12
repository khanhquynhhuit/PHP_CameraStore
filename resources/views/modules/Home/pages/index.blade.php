<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
        <!-- 1. Hero Banner Quảng cáo -->
        @include('modules.Home.components.hero-banner')

        <!-- 2. Danh mục máy ảnh nổi bật -->
        @php
            $featuredCategories = \App\Models\Category::withCount('products')->take(4)->get();
        @endphp
        @if ($featuredCategories->isNotEmpty())
            <div class="my-12">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Danh Mục Nổi Bật</h2>
                        <p class="text-sm text-gray-500 mt-1">Khám phá các thiết bị chuyên dụng theo từng phân khúc</p>
                    </div>
                    <a href="{{ route('categories.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                        Xem tất cả &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                    @foreach ($featuredCategories as $category)
                        <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
                           class="group p-5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-200 transition text-center flex flex-col items-center">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-3 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                                📷
                            </div>
                            <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition text-sm sm:text-base">
                                {{ $category->name }}
                            </h3>
                            <span class="text-xs text-gray-400 mt-1">
                                {{ $category->products_count }} sản phẩm
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 3. Sản phẩm mới nhất -->
        @php
            $latestProducts = \App\Models\Product::with(['productImages', 'category'])
                ->latest()
                ->take(8)
                ->get();
        @endphp

        <div class="my-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Sản Phẩm Mới Nhất</h2>
                    <p class="text-sm text-gray-500 mt-1">Các dòng máy ảnh thế hệ mới vừa cập bến cửa hàng</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                    Tất cả sản phẩm &rarr;
                </a>
            </div>

            @if ($latestProducts->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($latestProducts as $product)
                        @include('modules.Product.components.product-card', ['product' => $product])
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
                    <p class="text-gray-500">Chưa có sản phẩm nào. Vui lòng chạy seeder dữ liệu mẫu!</p>
                </div>
            @endif
        </div>

        <!-- 4. Banner Cam Kết / Lý do chọn Camera Store -->
        <div class="my-16 bg-white border border-gray-100 rounded-3xl p-8 sm:p-12 shadow-sm">
            <div class="max-w-2xl mx-auto text-center mb-10">
                <h2 class="text-2xl font-black text-gray-900">Tại Sao Chọn Chúng Tôi?</h2>
                <p class="text-sm text-gray-500 mt-2">Địa chỉ mua sắm thiết bị nhiếp ảnh uy tín hàng đầu cho giới sáng tạo nội dung</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4">
                        🛡️
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-1">Chính Hãng 100%</h3>
                    <p class="text-xs text-gray-500">Đầy đủ tem nhãn và thẻ bảo hành chính hãng từ nhà sản xuất quốc tế.</p>
                </div>

                <div class="flex flex-col items-center text-center p-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4">
                        🚚
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-1">Giao Hàng Siêu Tốc</h3>
                    <p class="text-xs text-gray-500">Đóng gói chuẩn chống sốc chuyên dụng cho máy ảnh, bảo hiểm vận chuyển 100%.</p>
                </div>

                <div class="flex flex-col items-center text-center p-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-4">
                        🔄
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-1">Hỗ Trợ Kỹ Thuật 24/7</h3>
                    <p class="text-xs text-gray-500">Đội ngũ kỹ thuật viên am hiểu nhiếp ảnh sẵn sàng hỗ trợ setup và hướng dẫn sử dụng.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
