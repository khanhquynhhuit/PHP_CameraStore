<x-app-layout>
    @php
        $isAdminRoute = request()->routeIs('admin.categories.*');
        $categoryImages = [
            'may-anh-mirrorless' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=400&q=80',
            'may-anh-dslr' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=400&q=80',
            'ong-kinh-lens' => 'https://images.unsplash.com/photo-1617005082133-548c4dd27f35?auto=format&fit=crop&w=400&q=80',
            'phu-kien-may-anh' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=400&q=80',
            'may-anh-compact' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=400&q=80',
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-heading font-bold text-xl text-ink leading-tight">
                    {{ $isAdminRoute ? __('Quản lý danh mục sản phẩm') : __('Danh mục thiết bị máy ảnh & phụ kiện') }}
                </h1>
                <p class="text-xs text-ink/60 mt-0.5">
                    {{ $isAdminRoute ? 'Quản lý các danh mục máy ảnh, ống kính và thiết lập hiển thị' : 'Khám phá các dòng máy ảnh Mirrorless, DSLR, Ống kính Lens và Phụ kiện chính hãng tại CameraStore.vn' }}
                </p>
            </div>
            @if ($isAdminRoute)
                <div>
                    <a href="{{ route('admin.categories.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-primary text-cream rounded-xl text-sm font-semibold hover:bg-primary-dark transition shadow-sm">
                        + Thêm danh mục mới
                    </a>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-sand/50 border border-ink/10 text-xs font-semibold text-ink">
                        <span>🗂️</span>
                        <span>Tổng số: <strong class="text-primary">{{ $danhSachDanhMuc->total() }}</strong> danh mục</span>
                    </span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">
            @if ($isAdminRoute)
                {{-- GIAO DIỆN QUẢN TRỊ ADMIN (CRUD) --}}
                {{-- Thanh tìm kiếm & bộ lọc Admin --}}
                <div class="bg-surface border border-ink/10 rounded-2xl p-4 shadow-xs">
                    <form method="GET" action="{{ route('admin.categories.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                        <div class="sm:col-span-6">
                            <label for="search" class="block text-xs font-semibold text-ink/70 mb-1">Tìm kiếm danh mục</label>
                            <div class="relative">
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Nhập tên hoặc slug danh mục..." 
                                       class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <label for="status" class="block text-xs font-semibold text-ink/70 mb-1">Trạng thái</label>
                            <select id="status" 
                                    name="status" 
                                    class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang kích hoạt</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tạm ẩn</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2 flex items-center gap-2">
                            <button type="submit" 
                                    class="w-full py-2 px-4 bg-ink text-cream rounded-xl text-sm font-semibold hover:bg-ink/80 transition text-center">
                                Lọc
                            </button>
                            @if(request('search') || request()->filled('status'))
                                <a href="{{ route('admin.categories.index') }}" 
                                   class="py-2 px-3 border border-ink/15 text-ink/70 rounded-xl text-sm font-medium hover:bg-sand/40 transition" 
                                   title="Đặt lại bộ lọc">
                                    ✕
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Bảng quản lý danh mục Admin --}}
                <div class="bg-surface border border-ink/10 rounded-2xl shadow-xs overflow-hidden">
                    @include('modules.Category.components.category-table')

                    @if ($danhSachDanhMuc->hasPages())
                        <div class="px-6 py-4 border-t border-ink/10 bg-sand/20">
                            {{ $danhSachDanhMuc->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            @else
                {{-- GIAO DIỆN MUA SẮM DÀNH CHO KHÁCH HÀNG (CUSTOMER SHOWCASE GRID) --}}
                {{-- Thanh tìm kiếm danh mục cho khách --}}
                <div class="bg-surface border border-ink/10 rounded-2xl p-4 shadow-xs">
                    <form method="GET" action="{{ route('categories.index') }}" class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Tìm kiếm danh mục máy ảnh hoặc phụ kiện..." 
                                   class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2.5 px-4 pl-10">
                            <span class="absolute left-3.5 top-3 text-ink/40 text-sm">🔍</span>
                        </div>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-primary text-cream rounded-xl text-sm font-semibold hover:bg-primary-dark transition shadow-xs">
                            Tìm danh mục
                        </button>
                        @if(request('search'))
                            <a href="{{ route('categories.index') }}" 
                               class="px-4 py-2.5 border border-ink/15 text-ink/70 rounded-xl text-sm font-medium hover:bg-sand/40 transition text-center">
                                Xóa tìm kiếm
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Lưới hiển thị Card Danh mục Mua sắm --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse ($danhSachDanhMuc as $dm)
                        @php
                            $catSlug = \Illuminate\Support\Str::slug($dm->name);
                            $catImg = $categoryImages[$catSlug] ?? 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=400&q=80';
                        @endphp
                        <a href="{{ route('products.index', ['category_id' => $dm->id]) }}" 
                           class="group bg-surface border border-ink/10 rounded-2xl overflow-hidden hover:border-primary hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                            {{-- Hình ảnh minh họa danh mục --}}
                            <div class="h-44 w-full bg-sand/30 overflow-hidden relative">
                                <img src="{{ $catImg }}" 
                                     alt="{{ $dm->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-ink/60 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                                <span class="absolute bottom-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-cream/90 text-ink shadow-xs">
                                    {{ $dm->products_count }} sản phẩm
                                </span>
                            </div>

                            {{-- Nội dung thẻ danh mục --}}
                            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                                <div>
                                    <h3 class="font-heading font-bold text-base text-ink group-hover:text-primary transition-colors">
                                        {{ $dm->name }}
                                    </h3>
                                    <p class="text-xs text-ink/60 mt-1 line-clamp-2">
                                        Khám phá các mẫu máy ảnh và thiết bị nhiếp ảnh thuộc danh mục {{ $dm->name }} chính hãng.
                                    </p>
                                </div>

                                <div class="pt-2 border-t border-ink/10 flex items-center justify-between text-xs font-semibold text-primary group-hover:translate-x-1 transition-transform">
                                    <span>Xem tất cả sản phẩm</span>
                                    <span>→</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-16 text-center bg-surface border border-ink/10 rounded-2xl">
                            <span class="text-4xl block mb-2">🗂️</span>
                            <h3 class="font-heading font-bold text-base text-ink">Không tìm thấy danh mục nào</h3>
                            <p class="text-xs text-ink/60 mt-1">Vui lòng thử lại với từ khóa tìm kiếm khác.</p>
                            <div class="mt-4">
                                <a href="{{ route('categories.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-sand text-ink rounded-xl text-xs font-semibold hover:bg-sand/70 transition">
                                    Xem tất cả danh mục
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Phân trang khách hàng --}}
                @if ($danhSachDanhMuc->hasPages())
                    <div class="pt-4">
                        {{ $danhSachDanhMuc->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    @push('scripts')
        @include('modules.Category.services.category-service')
    @endpush
</x-app-layout>
