<div class="relative bg-gradient-to-r from-gray-900 via-indigo-950 to-gray-900 text-white rounded-3xl overflow-hidden shadow-2xl my-6">
    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#6366f1_1px,transparent_1px)] [background-size:16px_16px]"></div>
    
    <div class="relative max-w-7xl mx-auto px-6 py-16 sm:py-24 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-semibold mb-6">
                <span>🔥 Khám phá công nghệ nhiếp ảnh đỉnh cao</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
                Ghi Lại Từng <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-sky-300 to-white">Khoảnh Khắc Vô Giá</span>
            </h1>

            <p class="mt-5 text-base sm:text-lg text-gray-300 max-w-xl leading-relaxed">
                Chuyên phân phối máy ảnh Mirrorless, DSLR, ống kính cao cấp từ các thương hiệu hàng đầu thế giới: Sony, Canon, Nikon, Fujifilm. Bảo hành chính hãng 100%.
            </p>

            <div class="mt-8 flex flex-wrap gap-4 items-center">
                <a href="{{ route('products.index') }}" 
                   class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                    Khám phá sản phẩm
                </a>
                <a href="{{ route('categories.index') }}" 
                   class="px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl backdrop-blur-md border border-white/10 transition">
                    Xem danh mục
                </a>
            </div>

            <!-- Cam kết dịch vụ -->
            <div class="mt-10 grid grid-cols-3 gap-4 border-t border-white/10 pt-6">
                <div>
                    <p class="text-xl sm:text-2xl font-bold text-white">100%</p>
                    <p class="text-xs text-gray-400">Chính hãng</p>
                </div>
                <div>
                    <p class="text-xl sm:text-2xl font-bold text-white">24 Tháng</p>
                    <p class="text-xs text-gray-400">Bảo hành</p>
                </div>
                <div>
                    <p class="text-xl sm:text-2xl font-bold text-white">Freeship</p>
                    <p class="text-xs text-gray-400">Toàn quốc</p>
                </div>
            </div>
        </div>

        <div class="relative hidden lg:block">
            <div class="w-full max-w-md mx-auto aspect-square rounded-2xl overflow-hidden shadow-2xl border border-white/10 transform rotate-2 hover:rotate-0 transition duration-500">
                <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80" 
                     alt="Camera Showcase" 
                     class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</div>
