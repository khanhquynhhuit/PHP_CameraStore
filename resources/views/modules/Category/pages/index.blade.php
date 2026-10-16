<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-heading font-bold text-xl text-ink leading-tight">
                    {{ __('Quản lý danh mục sản phẩm') }}
                </h1>
                <p class="text-xs text-ink/60 mt-0.5">
                    Quản lý các danh mục máy ảnh, ống kính và phụ kiện
                </p>
            </div>
            <div>
                <a href="{{ route('admin.categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary text-cream rounded-xl text-sm font-semibold hover:bg-primary-dark transition shadow-sm">
                    Thêm danh mục mới
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">
            {{-- Thanh tìm kiếm & bộ lọc --}}
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

            {{-- Bảng hiển thị danh sách --}}
            <div class="bg-surface border border-ink/10 rounded-2xl shadow-xs overflow-hidden">
                @include('modules.Category.components.category-table')

                @if ($danhSachDanhMuc->hasPages())
                    <div class="px-6 py-4 border-t border-ink/10 bg-sand/20">
                        {{ $danhSachDanhMuc->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        @include('modules.Category.services.category-service')
    @endpush
</x-app-layout>
