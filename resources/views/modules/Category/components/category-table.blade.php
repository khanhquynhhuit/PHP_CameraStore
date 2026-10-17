<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-ink/10 text-sm">
        <thead class="bg-sand/40">
            <tr>
                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Tên danh mục</th>
                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Đường dẫn (Slug)</th>
                <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-ink/70 uppercase tracking-wider">Số sản phẩm</th>
                <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-ink/70 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-ink/70 uppercase tracking-wider">Hành động</th>
            </tr>
        </thead>
        <tbody class="bg-surface divide-y divide-ink/10">
            @forelse ($danhSachDanhMuc as $danhMuc)
                <tr class="hover:bg-sand/20 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-ink/60">
                        #{{ $danhMuc->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-semibold text-ink">{{ $danhMuc->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-ink/70 font-mono text-xs">
                        {{ $danhMuc->slug }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sand text-ink/80">
                            {{ $danhMuc->products_count ?? 0 }} sản phẩm
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if ($danhMuc->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Đang kích hoạt
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                Tạm ẩn
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('admin.categories.edit', $danhMuc->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 rounded-lg border border-ink/15 text-xs font-semibold text-ink/80 hover:bg-sand/50 hover:text-primary transition">
                            Sửa
                        </a>

                        <form method="POST" 
                              action="{{ route('admin.categories.destroy', $danhMuc->id) }}" 
                              class="inline-block"
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục [{{ $danhMuc->name }}] không?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg border border-red-200 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-ink/50">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <span class="font-medium text-ink/70">Chưa có danh mục nào phù hợp.</span>
                            <a href="{{ route('admin.categories.create') }}" class="text-xs text-primary underline hover:text-primary-dark">
                                Thêm danh mục đầu tiên ngay
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
