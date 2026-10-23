<x-app-layout>
    @php
        $isAdmin = request()->routeIs('admin.products.*');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-heading font-bold text-xl text-ink leading-tight">
                    {{ $isAdmin ? __('Quản lý sản phẩm máy ảnh') : __('Danh sách máy ảnh & thiết bị số') }}
                </h1>
                <p class="text-xs text-ink/60 mt-0.5">
                    {{ $isAdmin ? 'Quản lý kho hàng, cập nhật giá niêm yết, tồn kho và hình ảnh sản phẩm' : 'Khám phá các dòng máy ảnh Mirrorless, DSLR và ống kính chính hãng tại CameraStore.vn' }}
                </p>
            </div>
            @if ($isAdmin)
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.inventory.index') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-ink/15 bg-surface text-ink rounded-xl text-sm font-semibold hover:bg-sand/40 hover:text-primary transition shadow-2xs">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Quản lý kho hàng (Đợt 3)</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-primary text-cream rounded-xl text-sm font-semibold hover:bg-primary-dark transition shadow-sm">
                        Thêm máy ảnh mới
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">
            {{-- Bộ lọc & Tìm kiếm đa tiêu chí --}}
            @include('modules.Product.components.product-filter')

            {{-- Thông tin tóm tắt kết quả tìm kiếm --}}
            <div class="flex items-center justify-between text-xs text-ink/70 px-1">
                <div>
                    Tìm thấy <strong class="text-primary font-bold text-sm">{{ $danhSachSanPham->total() }}</strong> sản phẩm
                    @if (request('keyword') || request('search'))
                        cho từ khóa: <span class="font-semibold text-ink">"{{ request('keyword') ?? request('search') }}"</span>
                    @endif
                </div>
                @if ($danhSachSanPham->total() > 0)
                    <div>
                        Hiển thị {{ $danhSachSanPham->firstItem() }} - {{ $danhSachSanPham->lastItem() }}
                    </div>
                @endif
            </div>

            {{-- Nội dung hiển thị --}}
            @if ($isAdmin)
                {{-- Giao diện Quản trị: Bảng quản lý chi tiết cho Admin & Nhân viên --}}
                <div class="bg-surface border border-ink/10 rounded-2xl shadow-xs overflow-hidden">
                    @include('modules.Product.components.product-table')

                    @if ($danhSachSanPham->hasPages())
                        <div class="px-6 py-4 border-t border-ink/10 bg-sand/20">
                            {{ $danhSachSanPham->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            @else
                {{-- Giao diện Khách hàng: Lưới hiển thị chuẩn 3 cột theo rubric phân công --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($danhSachSanPham as $sp)
                        @include('modules.Product.components.product-card', ['product' => $sp])
                    @empty
                        <div class="col-span-full py-16 text-center bg-surface border border-ink/10 rounded-2xl">
                            <h3 class="font-heading font-bold text-lg text-ink">Không tìm thấy sản phẩm máy ảnh nào</h3>
                            <p class="text-xs text-ink/60 mt-1 max-w-md mx-auto">
                                Không có sản phẩm nào khớp với tiêu chí tìm kiếm hoặc khoảng giá của bạn. Vui lòng thử tìm kiếm với từ khóa khác hoặc bấm nút bỏ lọc.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('products.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-sand text-ink rounded-xl text-xs font-semibold hover:bg-sand/70 transition">
                                    Xem tất cả sản phẩm
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Thanh phân trang giao diện Khách hàng --}}
                @if ($danhSachSanPham->hasPages())
                    <div class="mt-8 bg-surface p-4 border border-ink/10 rounded-2xl shadow-xs">
                        {{ $danhSachSanPham->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    @push('scripts')
        @include('modules.Product.services.product-service')
    @endpush
</x-app-layout>
