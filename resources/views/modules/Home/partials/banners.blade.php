<section aria-label="Ưu đãi nổi bật" class="my-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- ===== BANNER 1: SONY ALPHA 7 IV (Hero lớn - Nền Cam Đào, chữ trắng kem, hoa văn sóng mờ) ===== --}}
        <article class="lg:col-span-2 relative rounded-3xl overflow-hidden shadow-sm border border-primary-dark/30 bg-gradient-to-br from-primary to-primary-dark text-cream p-6 sm:p-10 flex flex-col justify-between min-h-[380px] lg:min-h-[440px] group">
            
            {{-- Khối chữ nằm ở tầng trên (z-10) chồng lên vùng ảnh --}}
            <div class="relative z-10 max-w-sm sm:max-w-md xl:max-w-lg">
                <span class="inline-block rounded-full bg-accent px-3 py-1 text-xs font-semibold uppercase tracking-wide text-ink shadow-xs mb-3">
                    Độc quyền tại camerastore.vn
                </span>

                <h2 class="font-heading text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl xl:text-5xl drop-shadow-sm">
                    Sony Alpha 7 IV
                </h2>

                <p class="mt-3 text-sm sm:text-base text-cream leading-relaxed font-medium">
                    Khám phá chuẩn mực nhiếp ảnh Hybrid thế hệ mới. Cảm biến 33MP Full-frame, quay 4K 60p 10-bit.
                </p>

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <span class="rounded-xl bg-cream px-4 py-2 font-heading text-2xl font-bold tabular-nums text-primary-dark xl:text-3xl shadow-sm">
                        51.990.000 đ
                    </span>
                    <span class="text-sm sm:text-base tabular-nums text-cream line-through font-medium">59.990.000 đ</span>
                    <span class="rounded-full bg-ink px-2.5 py-1 text-xs font-semibold text-cream shadow-xs">-13%</span>
                </div>
            </div>

            {{-- Các nút hành động phía dưới --}}
            <div class="relative z-10 mt-6 flex flex-wrap gap-3">
                <x-button :href="route('products.index')" variant="accent" size="lg">
                    Mua ngay
                    <x-phosphor-arrow-right-light class="h-5 w-5" />
                </x-button>
                <x-button :href="route('categories.index')" variant="outline-light" size="lg">Xem danh mục</x-button>
            </div>

            {{-- Lớp hoa văn sóng mờ mờ tinh tế --}}
            <div aria-hidden="true"
                 class="absolute inset-0 bg-repeat opacity-[0.09] pointer-events-none z-0"
                 style="background-image: url('{{ asset('images/pattern-white.png') }}'); background-size: 150px;"></div>

            {{-- Quầng sáng kem & Ảnh Sony Alpha 7 IV to lớn nằm ở tầng nền (z-0), máy ảnh lệch hẳn qua phải --}}
            <div aria-hidden="true"
                 class="absolute inset-0 bg-[radial-gradient(circle_at_90%_50%,rgba(255,250,245,0.22),transparent_65%)] pointer-events-none z-0"></div>
            
            <div class="absolute -right-20 sm:-right-16 lg:-right-10 -bottom-4 sm:-bottom-2 top-0 w-[55%] sm:w-[52%] flex items-center justify-end pointer-events-none z-0 p-2 translate-x-10 sm:translate-x-16 lg:translate-x-24 xl:translate-x-28">
                <img src="{{ asset('images/banners/sony-a7iv.webp') }}" 
                     alt="Máy ảnh Sony Alpha 7 IV kèm ống kính"
                     width="1200" height="900"
                     class="max-h-[300px] sm:max-h-[380px] lg:max-h-[440px] w-auto max-w-none object-contain drop-shadow-[0_20px_35px_rgba(0,0,0,0.5)] group-hover:scale-105 transition-transform duration-500">
            </div>
        </article>

        {{-- ===== 2 BANNER PHỤ BÊN PHẢI (Nền đậm sang trọng, font chữ trắng, hoa văn sóng mờ) ===== --}}
        <div class="flex flex-col gap-4">

            {{-- BANNER 2: CANON EOS R SERIES (Nền Xanh Navy sâu, chữ trắng tinh, hoa văn sóng) --}}
            <a href="{{ route('products.index', ['brand' => 'Canon']) }}"
               class="group relative rounded-3xl overflow-hidden border border-navy/40 bg-gradient-to-br from-[#263750] via-navy to-[#1E2C42] text-white p-5 sm:p-6 flex flex-col justify-between flex-1 min-h-[200px] shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent">
                
                {{-- Chữ màu trắng ở tầng trên (z-10) --}}
                <div class="relative z-10 max-w-[55%] sm:max-w-[58%]">
                    <span class="inline-block rounded-full bg-white/20 text-white backdrop-blur-xs px-3 py-1 text-xs font-semibold uppercase tracking-wide shadow-2xs">
                        Siêu ưu đãi
                    </span>
                    <h3 class="mt-2.5 font-heading text-xl sm:text-2xl font-bold leading-tight text-white drop-shadow-xs">
                        Canon EOS R Series
                    </h3>
                    <p class="mt-1.5 line-clamp-2 text-xs sm:text-sm text-white/85 leading-relaxed font-normal">
                        Giảm trực tiếp 3.000.000đ khi thanh toán thẻ.
                    </p>
                </div>

                <div class="relative z-10 mt-3">
                    <span class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-accent group-hover:text-white transition-colors">
                        Xem chi tiết
                        <x-phosphor-arrow-right-light class="h-4 w-4 transition-transform group-hover:translate-x-1" />
                    </span>
                </div>

                {{-- Hoa văn sóng trắng mờ mờ --}}
                <div aria-hidden="true"
                     class="absolute inset-0 bg-repeat opacity-[0.08] pointer-events-none z-0"
                     style="background-image: url('{{ asset('images/pattern-white.png') }}'); background-size: 130px;"></div>

                {{-- Quầng sáng nhẹ sau máy ảnh --}}
                <div aria-hidden="true"
                     class="absolute inset-0 bg-[radial-gradient(circle_at_75%_50%,rgba(255,255,255,0.12),transparent_65%)] pointer-events-none z-0"></div>

                {{-- Ảnh máy ảnh Canon to ở tầng nền (z-0) --}}
                <div class="absolute -right-3 -bottom-2 top-0 w-[52%] flex items-center justify-center pointer-events-none z-0">
                    <img src="{{ asset('images/banners/canon-eos-r.webp') }}"
                         alt="Máy ảnh Canon EOS R Series kèm ống kính RF"
                         width="800" height="600"
                         class="max-h-[175px] sm:max-h-[195px] w-auto max-w-none object-contain drop-shadow-[0_15px_25px_rgba(0,0,0,0.4)] group-hover:scale-110 transition-transform duration-300">
                </div>
            </a>

            {{-- BANNER 3: FUJIFILM X100 VI (Nền Đen Ấm Gunmetal, chữ trắng tinh, hoa văn sóng) --}}
            <a href="{{ route('products.index', ['brand' => 'Fujifilm']) }}"
               class="group relative rounded-3xl overflow-hidden border border-ink/40 bg-gradient-to-br from-[#332624] via-ink to-[#1C1413] text-white p-5 sm:p-6 flex flex-col justify-between flex-1 min-h-[200px] shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent">
                
                {{-- Chữ màu trắng ở tầng trên (z-10) --}}
                <div class="relative z-10 max-w-[58%] sm:max-w-[60%]">
                    <span class="inline-block rounded-full bg-accent text-ink px-3 py-1 text-xs font-semibold uppercase tracking-wide shadow-2xs">
                        Hot trend
                    </span>
                    <h3 class="mt-2.5 font-heading text-xl sm:text-2xl font-bold leading-tight text-white drop-shadow-xs">
                        Fujifilm X100 VI
                    </h3>
                    <p class="mt-1.5 line-clamp-2 text-xs sm:text-sm text-white/85 leading-relaxed font-normal">
                        Tone màu phim kinh điển, thiết kế cổ điển.
                    </p>
                </div>

                <div class="relative z-10 mt-3">
                    <span class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-accent group-hover:text-white transition-colors">
                        Đặt hàng ngay
                        <x-phosphor-arrow-right-light class="h-4 w-4 transition-transform group-hover:translate-x-1" />
                    </span>
                </div>

                {{-- Hoa văn sóng trắng mờ mờ --}}
                <div aria-hidden="true"
                     class="absolute inset-0 bg-repeat opacity-[0.08] pointer-events-none z-0"
                     style="background-image: url('{{ asset('images/pattern-white.png') }}'); background-size: 130px;"></div>

                {{-- Quầng sáng nhẹ sau máy ảnh --}}
                <div aria-hidden="true"
                     class="absolute inset-0 bg-[radial-gradient(circle_at_75%_50%,rgba(255,255,255,0.10),transparent_65%)] pointer-events-none z-0"></div>

                {{-- Ảnh máy ảnh Fujifilm to ở tầng nền (z-0) --}}
                <div class="absolute right-0 -bottom-1 top-0 w-[48%] flex items-center justify-center pointer-events-none z-0">
                    <img src="{{ asset('images/banners/fujifilm-x100vi.webp') }}"
                         alt="Máy ảnh Fujifilm X100 VI"
                         width="800" height="600"
                         class="max-h-[155px] sm:max-h-[175px] max-w-full object-contain drop-shadow-[0_15px_25px_rgba(0,0,0,0.4)] group-hover:scale-110 transition-transform duration-300">
                </div>
            </a>

        </div>
    </div>
</section>
