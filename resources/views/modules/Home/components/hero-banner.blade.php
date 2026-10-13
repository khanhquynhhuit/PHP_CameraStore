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

                <!-- Ảnh Canon EOS R thu nhỏ vừa vặn khung góc phải -->
                <div class="absolute right-1 bottom-0 top-0 w-[45%] flex items-center justify-end pointer-events-none z-0 pr-1">
                    <img src="{{ asset('images/banners/canon_eos_r.png') }}" 
                         alt="Canon EOS R" 
                         class="max-h-[135px] w-auto max-w-full object-contain filter drop-shadow-[0_12px_20px_rgba(0,0,0,0.6)] group-hover:scale-105 transition-transform duration-300">
                </div>
            </div>

            <!-- Banner phụ 2: FUJIFILM X100 VI -->
            <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-gray-950 via-slate-900 to-gray-900 text-white p-6 flex flex-col justify-between flex-1 shadow-md group">
                <div class="relative z-10 max-w-[58%]">
                    <span class="text-[11px] font-bold bg-amber-500 text-gray-900 px-2 py-0.5 rounded uppercase font-black">Hot Trend</span>
                    <h3 class="text-xl font-black mt-2 leading-tight">FUJIFILM X100 VI</h3>
                    <p class="text-xs text-gray-300 mt-1 line-clamp-2">Tone màu phim kinh điển, thiết kế cổ điển</p>
                </div>
                <div class="relative z-10 mt-3">
                    <a href="{{ route('products.index', ['brand' => 'Fujifilm']) }}" class="text-xs font-bold text-amber-400 hover:underline flex items-center gap-1">
                        Đặt hàng ngay &rarr;
                    </a>
                </div>

                <!-- Ảnh Fujifilm X100VI thu nhỏ gọn gàng, vừa khung -->
                <div class="absolute right-2 bottom-0 top-0 w-[42%] flex items-center justify-end pointer-events-none z-0 pr-1">
                    <img src="{{ asset('images/banners/fujifilm_x100vi.png') }}" 
                         alt="Fujifilm X100VI" 
                         class="max-h-[110px] w-auto max-w-full object-contain filter drop-shadow-[0_10px_20px_rgba(0,0,0,0.6)] group-hover:scale-105 transition-transform duration-300">
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Khối cam kết dịch vụ cao cấp (Thiết kế sang trọng, hiện đại với SVG icon) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <!-- Item 1: Chính hãng -->
        <div class="flex items-center gap-3.5 group">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 border border-red-100/60 shadow-sm group-hover:scale-110 group-hover:bg-red-600 group-hover:text-white transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="font-extrabold text-gray-900 text-xs sm:text-sm tracking-tight group-hover:text-red-600 transition">100% Chính Hãng</p>
                <p class="text-gray-500 text-[11px] mt-0.5">Bảo hành toàn quốc 24 tháng</p>
            </div>
        </div>

        <!-- Item 2: Trả góp -->
        <div class="flex items-center gap-3.5 group sm:border-l sm:border-gray-100 sm:pl-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100/60 shadow-sm group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <div>
                <p class="font-extrabold text-gray-900 text-xs sm:text-sm tracking-tight group-hover:text-blue-600 transition">Trả Góp 0% Lãi Suất</p>
                <p class="text-gray-500 text-[11px] mt-0.5">Duyệt hồ sơ nhanh 15 phút</p>
            </div>
        </div>

        <!-- Item 3: Miễn phí vận chuyển -->
        <div class="flex items-center gap-3.5 group md:border-l md:border-gray-100 md:pl-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100/60 shadow-sm group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
            <div>
                <p class="font-extrabold text-gray-900 text-xs sm:text-sm tracking-tight group-hover:text-emerald-600 transition">Giao Hàng Miễn Phí</p>
                <p class="text-gray-500 text-[11px] mt-0.5">Đóng thùng chống sốc chuẩn</p>
            </div>
        </div>

        <!-- Item 4: Đổi trả -->
        <div class="flex items-center gap-3.5 group sm:border-l sm:border-gray-100 sm:pl-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 border border-purple-100/60 shadow-sm group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div>
                <p class="font-extrabold text-gray-900 text-xs sm:text-sm tracking-tight group-hover:text-purple-600 transition">Đổi Trả Trong 7 Ngày</p>
                <p class="text-gray-500 text-[11px] mt-0.5">Lỗi 1 đổi 1 an tâm mua sắm</p>
            </div>
        </div>
    </div>
</div>
