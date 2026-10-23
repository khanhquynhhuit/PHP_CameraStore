<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-ink/60 hover:text-primary transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Quản lý sản phẩm
                    </a>
                    <span class="text-ink/30">/</span>
                    <span class="text-xs font-semibold text-primary">Quản lý kho hàng</span>
                </div>
                <h1 class="font-heading font-bold text-xl text-ink leading-tight mt-1 flex items-center gap-2.5">
                    <span>Quản lý kho máy ảnh & Tồn kho</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
                        Đợt 3
                    </span>
                </h1>
                <p class="text-xs text-ink/60 mt-0.5">
                    Theo dõi số lượng tồn kho theo thời gian thực, lọc máy ảnh sắp hết hàng và cập nhật nhanh tồn kho trực tiếp.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.products.index') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-ink/15 text-ink/80 rounded-xl text-xs font-semibold hover:bg-sand/40 transition">
                    <svg class="w-4 h-4 text-ink/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>Danh sách sản phẩm</span>
                </a>
                <a href="{{ route('admin.products.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-primary text-cream rounded-xl text-xs font-semibold hover:bg-primary-dark transition shadow-xs">
                    <svg class="w-4 h-4 text-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Thêm máy ảnh mới</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">
            {{-- Alert thông báo --}}
            <x-alert />

            {{-- 1. Thẻ thống kê KPI kho hàng --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Tổng sản phẩm & tổng lượng máy ảnh trong kho --}}
                <div class="bg-surface border border-ink/10 rounded-2xl p-4 shadow-xs flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium text-ink/60 uppercase tracking-wider">Tổng mẫu máy ảnh</div>
                        <div class="text-2xl font-bold font-heading text-ink mt-1">
                            {{ $thongKeKho['tong_san_pham'] }}
                        </div>
                        <div class="text-[11px] text-ink/50 mt-0.5">
                            Tổng tồn kho: <strong class="text-ink font-semibold">{{ number_format($thongKeKho['tong_ton_kho']) }}</strong> chiếc
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sand/60 text-ink/80 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>

                {{-- Tồn kho an toàn --}}
                <div class="bg-surface border border-emerald-500/20 rounded-2xl p-4 shadow-xs flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium text-emerald-700 uppercase tracking-wider">Tồn kho an toàn (≥ 5)</div>
                        <div class="text-2xl font-bold font-heading text-emerald-700 mt-1">
                            {{ $thongKeKho['an_toan'] }}
                        </div>
                        <div class="text-[11px] text-emerald-600 mt-0.5">
                            Sản phẩm nguồn hàng ổn định
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Cảnh báo sắp hết hàng (< 5) --}}
                <a href="{{ route('admin.inventory.index', ['stock_status' => 'low_stock']) }}" 
                   class="bg-surface border border-amber-500/30 hover:border-amber-500 rounded-2xl p-4 shadow-xs flex items-center justify-between transition group {{ request('stock_status') === 'low_stock' ? 'ring-2 ring-amber-500' : '' }}">
                    <div>
                        <div class="text-xs font-medium text-amber-700 uppercase tracking-wider flex items-center gap-1.5">
                            <span>Sắp hết hàng (< 5)</span>
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        </div>
                        <div class="text-2xl font-bold font-heading text-amber-700 mt-1">
                            {{ $thongKeKho['sap_het_hang'] }}
                        </div>
                        <div class="text-[11px] text-amber-600 mt-0.5 group-hover:underline">
                            Cần nhập thêm hàng ngay →
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </a>

                {{-- Đã hết hàng (0) --}}
                <a href="{{ route('admin.inventory.index', ['stock_status' => 'out_of_stock']) }}" 
                   class="bg-surface border border-rose-500/30 hover:border-rose-500 rounded-2xl p-4 shadow-xs flex items-center justify-between transition group {{ request('stock_status') === 'out_of_stock' ? 'ring-2 ring-rose-500' : '' }}">
                    <div>
                        <div class="text-xs font-medium text-rose-700 uppercase tracking-wider">Hết hàng (0 chiếc)</div>
                        <div class="text-2xl font-bold font-heading text-rose-700 mt-1">
                            {{ $thongKeKho['da_het_hang'] }}
                        </div>
                        <div class="text-[11px] text-rose-600 mt-0.5 group-hover:underline">
                            Đang dừng bán trên web →
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                </a>
            </div>

            {{-- 2. Bộ lọc & Tabs trạng thái kho --}}
            <div class="bg-surface border border-ink/10 rounded-2xl p-5 shadow-xs space-y-4">
                {{-- Quick Filter Tabs --}}
                <div class="flex flex-wrap items-center gap-2 border-b border-ink/10 pb-4">
                    <span class="text-xs font-semibold text-ink/70 mr-1">Bộ lọc nhanh:</span>
                    <a href="{{ route('admin.inventory.index', array_merge(request()->except('stock_status', 'page'), ['stock_status' => 'all'])) }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ (!request('stock_status') || request('stock_status') === 'all') ? 'bg-primary text-cream shadow-xs' : 'bg-sand/40 text-ink/70 hover:bg-sand/70' }}">
                        Tất cả ({{ $thongKeKho['tong_san_pham'] }})
                    </a>
                    <a href="{{ route('admin.inventory.index', array_merge(request()->except('stock_status', 'page'), ['stock_status' => 'low_stock'])) }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ request('stock_status') === 'low_stock' ? 'bg-amber-600 text-cream shadow-xs' : 'bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100' }}">
                        <span>⚠️ Sắp hết hàng (< 5)</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('stock_status') === 'low_stock' ? 'bg-white/20' : 'bg-amber-200' }}">
                            {{ $thongKeKho['sap_het_hang'] }}
                        </span>
                    </a>
                    <a href="{{ route('admin.inventory.index', array_merge(request()->except('stock_status', 'page'), ['stock_status' => 'out_of_stock'])) }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ request('stock_status') === 'out_of_stock' ? 'bg-rose-600 text-cream shadow-xs' : 'bg-rose-50 text-rose-800 border border-rose-200 hover:bg-rose-100' }}">
                        <span>❌ Hết hàng (0)</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('stock_status') === 'out_of_stock' ? 'bg-white/20' : 'bg-rose-200' }}">
                            {{ $thongKeKho['da_het_hang'] }}
                        </span>
                    </a>
                    <a href="{{ route('admin.inventory.index', array_merge(request()->except('stock_status', 'page'), ['stock_status' => 'in_stock'])) }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('stock_status') === 'in_stock' ? 'bg-emerald-600 text-cream shadow-xs' : 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' }}">
                        ✅ Tồn kho dồi dào ({{ $thongKeKho['an_toan'] }})
                    </a>

                    @if (request()->hasAny(['keyword', 'category_id', 'brand_id', 'stock_status', 'sort']))
                        <a href="{{ route('admin.inventory.index') }}" 
                           class="ms-auto text-xs text-rose-600 hover:underline font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Đặt lại bộ lọc
                        </a>
                    @endif
                </div>

                {{-- Form lọc chi tiết --}}
                <form method="GET" action="{{ route('admin.inventory.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                    <input type="hidden" name="stock_status" value="{{ request('stock_status', 'all') }}">

                    <div class="lg:col-span-4">
                        <label for="keyword" class="block text-xs font-semibold text-ink/70 mb-1">Tìm kiếm máy ảnh / SKU</label>
                        <input type="text" 
                               id="keyword" 
                               name="keyword" 
                               value="{{ request('keyword') ?? request('search') }}" 
                               placeholder="Tên máy ảnh hoặc mã SKU..." 
                               class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                    </div>

                    <div class="lg:col-span-3">
                        <label for="category_id" class="block text-xs font-semibold text-ink/70 mb-1">Danh mục</label>
                        <select id="category_id" 
                                name="category_id" 
                                class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                            <option value="">-- Tất cả danh mục --</option>
                            @foreach ($danhSachDanhMuc as $dm)
                                <option value="{{ $dm->id }}" {{ request('category_id') == $dm->id ? 'selected' : '' }}>
                                    {{ $dm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="brand_id" class="block text-xs font-semibold text-ink/70 mb-1">Thương hiệu</label>
                        <select id="brand_id" 
                                name="brand_id" 
                                class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                            <option value="">-- Tất cả thương hiệu --</option>
                            @foreach ($danhSachThuongHieu as $th)
                                <option value="{{ $th->id }}" {{ request('brand_id') == $th->id ? 'selected' : '' }}>
                                    {{ $th->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-3">
                        <label for="sort" class="block text-xs font-semibold text-ink/70 mb-1">Sắp xếp tồn kho</label>
                        <div class="flex items-center gap-2">
                            <select id="sort" 
                                    name="sort" 
                                    class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                                <option value="stock_asc" {{ request('sort', 'stock_asc') === 'stock_asc' ? 'selected' : '' }}>Tồn kho: Ít nhất trước</option>
                                <option value="stock_desc" {{ request('sort') === 'stock_desc' ? 'selected' : '' }}>Tồn kho: Nhiều nhất trước</option>
                                <option value="moi_nhat" {{ request('sort') === 'moi_nhat' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="ten_a_z" {{ request('sort') === 'ten_a_z' ? 'selected' : '' }}>Tên: A - Z</option>
                            </select>
                            <button type="submit" 
                                    class="py-2 px-3.5 bg-primary text-cream rounded-xl text-sm font-semibold hover:bg-primary-dark transition shadow-xs">
                                Lọc
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- 3. Bảng quản lý kho & Cập nhật tồn kho nhanh --}}
            <div class="bg-surface border border-ink/10 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-5 py-3.5 bg-sand/30 border-b border-ink/10 flex items-center justify-between">
                    <div class="text-xs text-ink/70">
                        Tìm thấy <strong class="text-primary font-bold">{{ $danhSachSanPham->total() }}</strong> máy ảnh phù hợp
                    </div>
                    <div class="text-xs text-ink/50">
                        Cập nhật trực tiếp số lượng tại cột <span class="font-semibold text-ink">"Cập nhật nhanh tồn kho"</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-ink/10 text-sm">
                        <thead class="bg-sand/40">
                            <tr>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">Sản phẩm</th>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">Mã SKU</th>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">Danh mục / Hãng</th>
                                <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold text-ink/70 uppercase">Giá bán</th>
                                <th scope="col" class="px-4 py-3.5 text-center text-xs font-semibold text-ink/70 uppercase">Trạng thái kho</th>
                                <th scope="col" class="px-4 py-3.5 text-center text-xs font-semibold text-ink/70 uppercase w-64">Cập nhật nhanh tồn kho</th>
                                <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold text-ink/70 uppercase">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-surface divide-y divide-ink/10">
                            @forelse ($danhSachSanPham as $sp)
                                @php
                                    $primaryImg = $sp->primary_image;
                                    $imgPath = $primaryImg ? $primaryImg->path : null;
                                    $stock = (int) ($sp->stock ?? 0);
                                @endphp
                                <tr class="hover:bg-sand/20 transition-colors">
                                    {{-- Cột hình ảnh + Tên --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-lg bg-base border border-ink/10 p-1 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                                @if ($imgPath)
                                                    <img src="{{ asset('storage/' . $imgPath) }}" 
                                                         alt="{{ $sp->name }}" 
                                                         class="max-w-full max-h-full object-contain"
                                                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80';">
                                                @else
                                                    <span class="text-xs text-ink/40">No img</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <a href="{{ route('admin.products.show', $sp->id) }}" class="font-bold text-ink hover:text-primary transition line-clamp-1">
                                                    {{ $sp->name }}
                                                </a>
                                                <span class="text-xs text-ink/50">Bảo hành: {{ $sp->warranty_months }} tháng</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- SKU --}}
                                    <td class="px-4 py-3 whitespace-nowrap font-mono text-xs text-ink/80">
                                        {{ $sp->sku }}
                                    </td>

                                    {{-- Danh mục & Hãng --}}
                                    <td class="px-4 py-3 whitespace-nowrap text-xs">
                                        <div class="font-semibold text-ink">{{ $sp->category?->name ?? 'N/A' }}</div>
                                        <div class="text-ink/60 uppercase">{{ $sp->brand?->name ?? 'N/A' }}</div>
                                    </td>

                                    {{-- Giá bán --}}
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <div class="font-bold text-primary">
                                            {{ number_format($sp->price, 0, ',', '.') }} đ
                                        </div>
                                    </td>

                                    {{-- Trạng thái kho badge --}}
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        @if ($stock <= 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                ❌ Hết hàng (0)
                                            </span>
                                        @elseif ($stock < 5)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                ⚠️ Sắp hết: còn {{ $stock }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                ✅ Còn {{ $stock }} chiếc
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Form cập nhật nhanh tồn kho --}}
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <form method="POST" 
                                              action="{{ route('admin.inventory.quick-update', $sp->id) }}" 
                                              class="flex items-center justify-center gap-1.5 quick-stock-form">
                                            @csrf
                                            @method('PATCH')
                                            <div class="relative w-24">
                                                <input type="number" 
                                                       name="stock" 
                                                       value="{{ $stock }}" 
                                                       min="0" 
                                                       step="1"
                                                       required
                                                       class="w-full text-center text-sm font-semibold rounded-lg border-ink/20 bg-base text-ink py-1 px-2 focus:ring-primary focus:border-primary">
                                            </div>
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-sand hover:bg-primary hover:text-cream text-ink text-xs font-semibold rounded-lg border border-ink/15 transition shadow-2xs" 
                                                    title="Lưu số lượng tồn kho mới">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>Lưu</span>
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Thao tác --}}
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-xs space-x-1">
                                        <a href="{{ route('admin.products.edit', $sp->id) }}" 
                                           class="inline-flex items-center px-2.5 py-1 rounded-lg border border-ink/15 text-xs font-semibold text-ink/80 hover:bg-sand/40 hover:text-primary transition">
                                            Sửa chi tiết
                                        </a>
                                        <a href="{{ route('products.show', $sp->id) }}" 
                                           target="_blank" 
                                           class="inline-flex items-center px-2 py-1 rounded-lg border border-ink/15 text-ink/60 hover:bg-sand/40 transition" 
                                           title="Xem trang sản phẩm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-ink/50">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="w-8 h-8 text-ink/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <span class="font-medium text-ink/70">Không có máy ảnh nào khớp với bộ lọc tồn kho hiện tại.</span>
                                            <a href="{{ route('admin.inventory.index') }}" class="text-xs text-primary underline">
                                                Xem toàn bộ kho hàng
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($danhSachSanPham->hasPages())
                    <div class="px-6 py-4 border-t border-ink/10 bg-sand/20">
                        {{ $danhSachSanPham->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
