<div class="my-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Banner chính (Main Slider Banner) -->
        <div class="lg:col-span-2 relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-r from-gray-950 via-gray-900 to-black text-white p-8 sm:p-12 flex flex-col justify-between min-h-[340px]">
            <div class="relative z-10 max-w-lg">
                <span class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-black rounded uppercase tracking-wider mb-3">
                    Độc quyền tại KYMA
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                    SONY ALPHA 7 IV
                </h1>
                <p class="text-sm sm:text-base text-gray-300 mt-2 font-medium">
                    Khám phá chuẩn mực nhiếp ảnh Hybird thế hệ mới. Cảm biến 33MP Full-frame, quay 4K 60p 10-bit.
                </p>
                <div class="mt-4 flex items-baseline gap-3">
                    <span class="text-2xl sm:text-3xl font-black text-red-500">51.990.000 đ</span>
                    <span class="text-sm text-gray-400 line-through">59.990.000 đ</span>
                </div>
            </div>

            <div class="relative z-10 mt-6 flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" 
                   class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-lg shadow-red-600/30 transition">
                    Mua ngay &rarr;
                </a>
                <a href="{{ route('categories.index') }}" 
                   class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-lg backdrop-blur-sm transition">
                    Xem danh mục
                </a>
            </div>

            <!-- Background hình camera mờ nghệ thuật -->
            <div class="absolute right-0 bottom-0 top-0 w-1/2 opacity-30 lg:opacity-60 pointer-events-none flex items-center justify-center">
                <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80" 
                     alt="Camera Banner" 
                     class="w-full h-full object-cover mix-blend-screen">
            </div>
        </div>

        <!-- 2 Banner phụ bên phải (Đặc trưng layout Kyma.vn) -->
        <div class="flex flex-col gap-4">
            <!-- Banner phụ 1 -->
            <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-red-700 to-rose-900 text-white p-6 flex flex-col justify-between flex-1 shadow-md">
                <div>
                    <span class="text-[11px] font-bold bg-white/20 px-2 py-0.5 rounded text-white uppercase">Siêu ưu đãi</span>
                    <h3 class="text-xl font-black mt-2">CANON EOS R SERIES</h3>
                    <p class="text-xs text-red-100 mt-1">Giảm trực tiếp 3.000.000đ khi thanh toán thẻ</p>
                </div>
                <div class="mt-3">
                    <a href="{{ route('products.index', ['brand' => 'Canon']) }}" class="text-xs font-bold text-white hover:underline flex items-center gap-1">
                        Xem chi tiết &rarr;
                    </a>
                </div>
            </div>

            <!-- Banner phụ 2 -->
            <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-gray-900 to-slate-800 text-white p-6 flex flex-col justify-between flex-1 shadow-md">
                <div>
                    <span class="text-[11px] font-bold bg-amber-500 text-gray-900 px-2 py-0.5 rounded uppercase font-black">Hot Trend</span>
                    <h3 class="text-xl font-black mt-2">FUJIFILM X100 VI</h3>
                    <p class="text-xs text-gray-300 mt-1">Tone màu phim kinh điển, thiết kế cổ điển</p>
                </div>
                <div class="mt-3">
                    <a href="{{ route('products.index', ['brand' => 'Fujifilm']) }}" class="text-xs font-bold text-amber-400 hover:underline flex items-center gap-1">
                        Đặt hàng ngay &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Khối chính sách nổi bật dưới banner (Giống Kyma.vn) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-xs">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🛡️</span>
            <div>
                <p class="font-bold text-gray-900">100% Chính Hãng</p>
                <p class="text-gray-500 text-[11px]">Bảo hành toàn quốc 24T</p>
            </div>
        </div>
        <div class="flex items-center gap-3 border-l border-gray-100 pl-3">
            <span class="text-2xl">💳</span>
            <div>
                <p class="font-bold text-gray-900">Trả Góp 0% Lãi Suất</p>
                <p class="text-gray-500 text-[11px]">Hồ sơ nhanh trong 15 phút</p>
            </div>
        </div>
        <div class="flex items-center gap-3 border-l border-gray-100 pl-3">
            <span class="text-2xl">🚚</span>
            <div>
                <p class="font-bold text-gray-900">Giao Hàng Miễn Phí</p>
                <p class="text-gray-500 text-[11px]">Đơn hàng từ 1.000.000 đ</p>
            </div>
        </div>
        <div class="flex items-center gap-3 border-l border-gray-100 pl-3">
            <span class="text-2xl">🔄</span>
            <div>
                <p class="font-bold text-gray-900">Đổi Trả Trong 7 Ngày</p>
                <p class="text-gray-500 text-[11px]">Lỗi 1 đổi 1 an tâm mua sắm</p>
            </div>
        </div>
    </div>
</div>
