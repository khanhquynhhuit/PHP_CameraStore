@php
    $isAdminRoute = request()->routeIs('admin.products.*');
    $actionRoute = $isAdminRoute ? route('admin.products.index') : route('products.index');
    $hasActiveFilter = request()->filled('keyword') || request()->filled('search') || request()->filled('category_id') || request()->filled('brand_id') || request()->filled('brand') || request()->filled('min_price') || request()->filled('max_price') || (request('sort') && request('sort') !== 'moi_nhat') || request()->filled('status');
@endphp

<div class="bg-surface border border-ink/10 p-5 rounded-2xl shadow-xs space-y-4">
    <form method="GET" action="{{ $actionRoute }}" id="productFilterForm">
        {{-- Dòng 1: Tìm kiếm từ khóa, Danh mục, Thương hiệu & Sắp xếp --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            {{-- Tìm theo tên / SKU --}}
            <div class="lg:col-span-4">
                <label for="keyword" class="block text-xs font-semibold text-ink/70 mb-1">
                    Từ khóa tìm kiếm
                </label>
                <div class="relative">
                    <input type="text" 
                           id="keyword" 
                           name="keyword" 
                           value="{{ request('keyword') ?? request('search') }}" 
                           placeholder="Nhập tên máy ảnh, ống kính hoặc SKU..." 
                           class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                </div>
            </div>

            {{-- Lọc theo danh mục --}}
            <div class="lg:col-span-3">
                <label for="category_id" class="block text-xs font-semibold text-ink/70 mb-1">
                    Danh mục sản phẩm
                </label>
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

            {{-- Lọc theo thương hiệu --}}
            <div class="lg:col-span-2">
                <label for="brand_id" class="block text-xs font-semibold text-ink/70 mb-1">
                    Thương hiệu
                </label>
                <select id="brand_id" 
                        name="brand_id" 
                        class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                    <option value="">-- Tất cả hãng --</option>
                    @foreach ($danhSachThuongHieu as $th)
                        <option value="{{ $th->id }}" {{ (request('brand_id') == $th->id || request('brand') == $th->name) ? 'selected' : '' }}>
                            {{ $th->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sắp xếp --}}
            <div class="lg:col-span-3">
                <label for="sort" class="block text-xs font-semibold text-ink/70 mb-1">
                    Sắp xếp theo
                </label>
                <select id="sort" 
                        name="sort" 
                        class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                    <option value="moi_nhat" {{ request('sort') === 'moi_nhat' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="gia_tang_dan" {{ request('sort') === 'gia_tang_dan' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                    <option value="gia_giam_dan" {{ request('sort') === 'gia_giam_dan' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                    <option value="ten_a_z" {{ request('sort') === 'ten_a_z' ? 'selected' : '' }}>Tên: A - Z</option>
                </select>
            </div>
        </div>

        {{-- Dòng 2: Khoảng giá & Trạng thái --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end pt-3 border-t border-ink/10 mt-3">
            {{-- Giá từ --}}
            <div class="lg:col-span-3">
                <label for="min_price" class="block text-xs font-semibold text-ink/70 mb-1">
                    Giá tối thiểu (VNĐ)
                </label>
                <input type="number" 
                       id="min_price" 
                       name="min_price" 
                       value="{{ request('min_price') }}" 
                       placeholder="VD: 10000000" 
                       min="0"
                       step="500000"
                       class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
            </div>

            {{-- Giá đến --}}
            <div class="lg:col-span-3">
                <label for="max_price" class="block text-xs font-semibold text-ink/70 mb-1">
                    Giá tối đa (VNĐ)
                </label>
                <input type="number" 
                       id="max_price" 
                       name="max_price" 
                       value="{{ request('max_price') }}" 
                       placeholder="VD: 80000000" 
                       min="0"
                       step="500000"
                       class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
            </div>

            @if ($isAdminRoute)
                {{-- Trạng thái dành cho Admin --}}
                <div class="lg:col-span-3">
                    <label for="status" class="block text-xs font-semibold text-ink/70 mb-1">
                        Trạng thái kinh doanh
                    </label>
                    <select id="status" 
                            name="status" 
                            class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang kinh doanh</option>
                        <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Ẩn khỏi cửa hàng</option>
                    </select>
                </div>
            @else
                {{-- Nút chọn nhanh còn hàng cho khách --}}
                <div class="lg:col-span-3 pb-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-ink">
                        <input type="checkbox" 
                               name="in_stock" 
                               value="1" 
                               {{ request('in_stock') ? 'checked' : '' }}
                               class="rounded border-ink/20 text-primary focus:ring-primary">
                        <span>Chỉ xem sản phẩm còn hàng</span>
                    </label>
                </div>
            @endif

            {{-- Nút hành động Lọc & Đặt lại --}}
            <div class="lg:col-span-3 flex items-center gap-2">
                <button type="submit" 
                        class="flex-1 py-2 px-4 bg-primary text-cream rounded-xl text-sm font-semibold hover:bg-primary-dark transition text-center shadow-xs">
                    Áp dụng bộ lọc
                </button>
                @if ($hasActiveFilter)
                    <a href="{{ $actionRoute }}" 
                       class="py-2 px-3 border border-ink/15 text-ink/70 rounded-xl text-sm font-medium hover:bg-sand/40 transition whitespace-nowrap" 
                       title="Xóa toàn bộ bộ lọc">
                        ✕ Bỏ lọc
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>
