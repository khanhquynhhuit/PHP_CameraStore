<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-ink/10 text-sm">
        <thead class="bg-sand/40">
            <tr>
                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">Sản phẩm</th>
                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">SKU</th>
                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">Danh mục / Hãng</th>
                <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold text-ink/70 uppercase">Giá bán</th>
                <th scope="col" class="px-4 py-3.5 text-center text-xs font-semibold text-ink/70 uppercase">Tồn kho</th>
                <th scope="col" class="px-4 py-3.5 text-center text-xs font-semibold text-ink/70 uppercase">Trạng thái</th>
                <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold text-ink/70 uppercase">Hành động</th>
            </tr>
        </thead>
        <tbody class="bg-surface divide-y divide-ink/10">
            @forelse ($danhSachSanPham as $sp)
                @php
                    $primaryImg = $sp->primary_image;
                    $imgPath = $primaryImg ? $primaryImg->path : null;
                    $isStockEmpty = ($sp->stock ?? 0) <= 0;
                    $statusValue = is_object($sp->status) ? $sp->status->value : $sp->status;
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
                                <span class="text-xs text-ink/50">BH: {{ $sp->warranty_months }} tháng</span>
                            </div>
                        </div>
                    </td>

                    {{-- SKU --}}
                    <td class="px-4 py-3 whitespace-nowrap font-mono text-xs text-ink/80">
                        {{ $sp->sku }}
                    </td>

                    {{-- Danh mục & Thương hiệu --}}
                    <td class="px-4 py-3 whitespace-nowrap text-xs">
                        <div class="font-semibold text-ink">{{ $sp->category?->name ?? 'N/A' }}</div>
                        <div class="text-ink/60 uppercase">{{ $sp->brand?->name ?? 'N/A' }}</div>
                    </td>

                    {{-- Giá bán --}}
                    <td class="px-4 py-3 whitespace-nowrap text-right">
                        <div class="font-bold text-primary">
                            {{ number_format($sp->price, 0, ',', '.') }} đ
                        </div>
                        @if ($sp->original_price && $sp->original_price > $sp->price)
                            <div class="text-[11px] text-ink/40 line-through">
                                {{ number_format($sp->original_price, 0, ',', '.') }} đ
                            </div>
                        @endif
                    </td>

                    {{-- Tồn kho --}}
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        @if ($isStockEmpty)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-danger text-danger-ink">
                                Hết hàng (0)
                            </span>
                        @elseif ($sp->stock < 5)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                Cảnh báo: còn {{ $sp->stock }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Còn {{ $sp->stock }} chiếc
                            </span>
                        @endif
                    </td>

                    {{-- Trạng thái kinh doanh --}}
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        @if ($statusValue === 'active')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                Đang kinh doanh
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                Tạm ẩn
                            </span>
                        @endif
                    </td>

                    {{-- Hành động --}}
                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium space-x-1">
                        <a href="{{ route('products.show', $sp->id) }}" 
                           target="_blank"
                           class="inline-flex items-center px-2.5 py-1 rounded-lg border border-ink/15 text-xs text-ink/70 hover:bg-sand/40" 
                           title="Xem trên trang khách">
                            Xem
                        </a>

                        <a href="{{ route('admin.products.edit', $sp->id) }}" 
                           class="inline-flex items-center px-2.5 py-1 rounded-lg border border-ink/15 text-xs font-semibold text-ink/80 hover:bg-sand/40 hover:text-primary transition">
                            Sửa
                        </a>

                        <form method="POST" 
                              action="{{ route('admin.products.destroy', $sp->id) }}" 
                              class="inline-block" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm [{{ $sp->name }}] không?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg border border-red-200 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-ink/50">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <span class="font-medium text-ink/70">Chưa có sản phẩm nào phù hợp với bộ lọc.</span>
                            <a href="{{ route('admin.products.create') }}" class="text-xs text-primary underline hover:text-primary-dark">
                                Thêm sản phẩm máy ảnh mới ngay
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
