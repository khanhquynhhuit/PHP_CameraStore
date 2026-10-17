<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading font-bold text-xl text-ink leading-tight">
                    {{ __('Thêm danh mục mới') }}
                </h1>
                <p class="text-xs text-ink/60 mt-0.5">
                    Tạo mới nhóm danh mục cho các dòng sản phẩm máy ảnh
                </p>
            </div>
            <a href="{{ route('admin.categories.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-ink/15 text-xs font-semibold text-ink/80 hover:bg-sand/40 transition">
                ← Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-surface border border-ink/10 shadow-xs sm:rounded-2xl p-6 sm:p-8">
                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-6">
                    @csrf

                    {{-- Tên danh mục --}}
                    <x-form-input 
                        name="name" 
                        label="Tên danh mục" 
                        placeholder="VD: Máy ảnh Mirrorless, Máy ảnh DSLR, Ống kính (Lens)..." 
                        required 
                    />

                    {{-- Đường dẫn tĩnh (Slug) --}}
                    <x-form-input 
                        name="slug" 
                        label="Đường dẫn tĩnh (Slug)" 
                        placeholder="VD: may-anh-mirrorless (để trống hệ thống sẽ tự động tạo từ tên)" 
                    />

                    {{-- Trạng thái danh mục --}}
                    <x-form-select 
                        name="is_active" 
                        label="Trạng thái kích hoạt" 
                        :options="[
                            '1' => 'Đang kích hoạt (Hiển thị trên website)',
                            '0' => 'Tạm ẩn (Không hiển thị)'
                        ]" 
                        :selected="old('is_active', '1')" 
                        :placeholder="null"
                        required 
                    />

                    {{-- Nút bấm hành động --}}
                    <div class="pt-6 border-t border-ink/10 flex items-center gap-4">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 bg-primary text-cream rounded-xl text-sm font-semibold hover:bg-primary-dark transition shadow-sm">
                            Lưu danh mục
                        </button>
                        <a href="{{ route('admin.categories.index') }}" 
                           class="text-sm font-medium text-ink/60 hover:text-ink transition">
                            Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
