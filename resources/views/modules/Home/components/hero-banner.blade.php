<div class="my-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Banner chính (Main Slider Banner: Sony Alpha 7 IV) -->
        <div class="lg:col-span-2 relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-r from-gray-950 via-gray-900 to-black text-white p-8 sm:p-12 flex flex-col justify-between min-h-[340px] group">
            <div class="relative z-10 max-w-lg">
                <span class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-black rounded uppercase tracking-wider mb-3">
                    Độc quyền tại KYMA
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                    SONY ALPHA 7 IV
                </h1>
                <p class="text-sm sm:text-base text-gray-300 mt-2 font-medium">
                    Khám phá chuẩn mực nhiếp ảnh Hybrid thế hệ mới. Cảm biến 33MP Full-frame, quay 4K 60p 10-bit.
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

            <!-- Ảnh Sony Alpha 7 IV đã xóa nền trong suốt to đầy đặn -->
            <div class="absolute -right-4 -bottom-4 top-0 w-[55%] flex items-center justify-end pointer-events-none p-2 z-0">
                <img src="{{ asset('images/banners/sony_a7iv.png') }}" 
                     alt="Sony Alpha 7 IV" 
                     class="max-h-[380px] w-auto max-w-none object-contain filter drop-shadow-[0_25px_35px_rgba(0,0,0,0.9)] group-hover:scale-105 transition-transform duration-500">
            </div>
        </div>

        <!-- 2 Banner phụ bên phải (Có ảnh tách nền trong suốt) -->
        <div class="flex flex-col gap-4">
            <!-- Banner phụ 1: CANON EOS R SERIES -->
            <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-red-700 via-rose-800 to-red-900 text-white p-6 flex flex-col justify-between flex-1 shadow-md group">
                <div class="relative z-10 max-w-[55%]">
                    <span class="text-[11px] font-bold bg-white/20 px-2 py-0.5 rounded text-white uppercase">Siêu ưu đãi</span>
                    <h3 class="text-xl font-black mt-2 leading-tight">CANON EOS R SERIES</h3>
                    <p class="text-xs text-red-100 mt-1 line-clamp-2">Giảm trực tiếp 3.000.000đ khi thanh toán thẻ</p>
                </div>
                <div class="relative z-10 mt-3">
                    <a href="{{ route('products.index', ['brand' => 'Canon']) }}" class="text-xs font-bold text-white hover:underline flex items-center gap-1">
                        Xem chi tiết &rarr;
                    </a>
                </div>

                <!-- Ảnh Canon EOS R to đầy đặn vừa khung -->
                <div class="absolute -right-3 -bottom-2 top-0 w-[50%] flex items-center justify-center pointer-events-none z-0">
                    <img src="{{ asset('images/banners/canon_eos_r.png') }}" 
                         alt="Canon EOS R" 
                         class="max-h-[175px] w-auto max-w-none object-contain filter drop-shadow-[0_15px_25px_rgba(0,0,0,0.6)] group-hover:scale-110 transition-transform duration-300">
                </div>
            </div>

            <!-- Banner phụ 2: FUJIFILM X100 VI -->
            <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-gray-950 via-slate-900 to-gray-900 text-white p-6 flex flex-col justify-between flex-1 shadow-md group">
                <div class="relative z-10 max-w-[60%]">
                    <span class="text-[11px] font-bold bg-amber-500 text-gray-900 px-2 py-0.5 rounded uppercase font-black">Hot Trend</span>
                    <h3 class="text-xl font-black mt-2 leading-tight">FUJIFILM X100 VI</h3>
                    <p class="text-xs text-gray-300 mt-1 line-clamp-2">Tone màu phim kinh điển, thiết kế cổ điển</p>
                </div>
                <div class="relative z-10 mt-3">
                    <a href="{{ route('products.index', ['brand' => 'Fujifilm']) }}" class="text-xs font-bold text-amber-400 hover:underline flex items-center gap-1">
                        Đặt hàng ngay &rarr;
                    </a>
                </div>

                <!-- Ảnh Fujifilm X100VI đã tách nền trong suốt -->
                <div class="absolute right-2 bottom-1 top-1 w-[45%] flex items-center justify-center pointer-events-none z-0">
                    <img src="{{ asset('images/banners/fujifilm_x100vi.png') }}" 
                         alt="Fujifilm X100VI" 
                         class="max-h-[135px] max-w-full object-contain filter drop-shadow-[0_10px_20px_rgba(0,0,0,0.6)] group-hover:scale-110 transition-transform duration-300">
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Khối chính sách nổi bật dưới banner -->
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
