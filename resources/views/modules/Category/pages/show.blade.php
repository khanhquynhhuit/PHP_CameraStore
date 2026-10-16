<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading font-bold text-xl text-ink leading-tight">
                    {{ __('Chi tiết danh mục') }}: <span class="text-primary">{{ $danhMuc->name }}</span>
                </h1>
                <p class="text-xs text-ink/60 mt-0.5">
                    Mã danh mục: #{{ $danhMuc->id }} | Slug: {{ $danhMuc->slug }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.categories.edit', $danhMuc->id) }}" 
                   class="inline-flex items-center px-3 py-1.5 rounded-xl bg-primary text-cream text-xs font-semibold hover:bg-primary-dark transition">
                    Sửa danh mục
                </a>
                <a href="{{ route('admin.categories.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 rounded-xl border border-ink/15 text-xs font-semibold text-ink/80 hover:bg-sand/40 transition">
                    ← Danh sách
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">
            {{-- Thẻ tóm tắt thông tin danh mục --}}
            <div class="bg-surface border border-ink/10 rounded-2xl p-6 shadow-xs grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <span class="text-xs font-semibold text-ink/60 uppercase">Tên danh mục</span>
                    <p class="text-lg font-bold text-ink mt-1">{{ $danhMuc->name }}</p>
                </div>
                <div>
                    <span class="text-xs font-semibold text-ink/60 uppercase">Trạng thái</span>
                    <div class="mt-1">
                        @if ($danhMuc->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Đang kích hoạt
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                Tạm ẩn
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <span class="text-xs font-semibold text-ink/60 uppercase">Tổng số sản phẩm</span>
                    <p class="text-lg font-bold text-primary mt-1">{{ $danhMuc->products_count ?? $danhMuc->products()->count() }} sản phẩm</p>
                </div>
            </div>

            {{-- Danh sách các sản phẩm thuộc danh mục này --}}
            <div class="bg-surface border border-ink/10 rounded-2xl p-6 shadow-xs">
                <h3 class="font-heading font-bold text-base text-ink mb-4">Danh sách sản phẩm thuộc danh mục</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-ink/10 text-sm">
                        <thead class="bg-sand/30">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-ink/70 uppercase">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-ink/70 uppercase">Tên máy ảnh</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-ink/70 uppercase">Mã SKU</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-ink/70 uppercase">Giá bán</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-ink/70 uppercase">Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink/10">
                            @forelse ($danhMuc->products as $sanPham)
                                <tr class="hover:bg-sand/20">
                                    <td class="px-4 py-3 text-ink/60">#{{ $sanPham->id }}</td>
                                    <td class="px-4 py-3 font-semibold text-ink">{{ $sanPham->name }}</td>
                                    <td class="px-4 py-3 font-mono text-xs text-ink/70">{{ $sanPham->sku }}</td>
                                    <td class="px-4 py-3 text-right font-medium text-primary">
                                        {{ number_format($sanPham->price, 0, ',', '.') }} đ
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 rounded text-xs {{ $sanPham->stock > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                                            {{ $sanPham->stock }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-ink/50 text-xs">
                                        Chưa có sản phẩm nào được liên kết với danh mục này.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
