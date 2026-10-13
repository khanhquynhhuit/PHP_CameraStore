<div x-data="{
    items: {{ json_encode($items->map(function($item) {
        return [
            'id' => $item->id,
            'price' => (float) $item->product->price,
            'quantity' => (int) $item->quantity,
            'selected' => true
        ];
    })) }},
    selectAll: true,
    get selectedItems() {
        return this.items.filter(i => i.selected);
    },
    get selectedCount() {
        return this.selectedItems.reduce((sum, i) => sum + i.quantity, 0);
    },
    get selectedSubtotal() {
        return this.selectedItems.reduce((sum, i) => sum + (i.price * i.quantity), 0);
    },
    toggleSelectAll() {
        this.selectAll = !this.selectAll;
        this.items.forEach(i => i.selected = this.selectAll);
    },
    updateSelectAllState() {
        this.selectAll = this.items.length > 0 && this.items.every(i => i.selected);
    }
}" 
class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Cột trái: Danh sách sản phẩm kèm Checkbox -->
    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm rounded-xl p-4 sm:p-6 border border-gray-200">
        <!-- Thanh Chọn tất cả (Select All Header) -->
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-200">
            <label class="flex items-center gap-3 cursor-pointer select-none">
                <input type="checkbox" 
                       x-model="selectAll" 
                       @change="toggleSelectAll()" 
                       class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                <span class="text-sm font-bold text-gray-800">
                    Chọn tất cả (<span x-text="items.length"></span> sản phẩm)
                </span>
            </label>

            <span class="text-xs text-gray-500">
                Đã chọn: <strong class="text-red-600" x-text="selectedItems.length"></strong> món
            </span>
        </div>

        <!-- Danh sách từng sản phẩm -->
        <div class="divide-y divide-gray-200">
            @forelse ($items as $index => $item)
                <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <!-- Checkbox + Ảnh + Tên sản phẩm -->
                    <div class="flex items-center gap-3">
                        <!-- Checkbox chọn mua sản phẩm này -->
                        <input type="checkbox" 
                               x-model="items[{{ $index }}].selected" 
                               @change="updateSelectAllState()" 
                               class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500 shrink-0">

                        <a href="{{ route('products.show', $item->product->id) }}" class="shrink-0">
                            @php
                                $image = $item->product->primary_image;
                                $imageUrl = $image ? asset('storage/' . $image->image_url) : 'https://placehold.co/100x100?text=Camera';
                            @endphp
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-16 h-16 sm:w-20 sm:h-20 object-contain rounded-lg border border-gray-200 p-1"
                                 onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Camera';">
                        </a>

                        <div>
                            <a href="{{ route('products.show', $item->product->id) }}" class="font-bold text-gray-900 hover:text-red-600 transition text-sm sm:text-base line-clamp-2">
                                {{ $item->product->name }}
                            </a>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-400">SKU: {{ $item->product->sku ?? 'N/A' }}</span>
                                <span class="text-xs px-2 py-0.5 rounded {{ $item->product->stock > 0 ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'bg-red-50 text-red-600 font-bold' }}">
                                    {{ $item->product->stock > 0 ? 'Còn ' . $item->product->stock . ' máy' : 'Hết hàng' }}
                                </span>
                            </div>
                            <p class="text-sm font-black text-red-600 mt-1">
                                {{ number_format($item->product->price, 0, ',', '.') }} đ
                            </p>
                        </div>
                    </div>

                    <!-- Tăng giảm số lượng và Nút Xóa -->
                    <div class="flex items-center justify-between sm:justify-end gap-4 border-t sm:border-t-0 pt-2 sm:pt-0 pl-7 sm:pl-0">
                        <!-- Bộ chọn số lượng -->
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-gray-50">
                            <form method="POST" action="{{ route('cart.update', $item->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                <button type="submit" 
                                        class="px-2.5 py-1 text-gray-600 hover:bg-gray-200 font-bold"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    -
                                </button>
                            </form>

                            <span class="w-12 text-center text-sm font-bold py-1 bg-white border-x border-gray-200">
                                {{ $item->quantity }}
                            </span>

                            <form method="POST" action="{{ route('cart.update', $item->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                <button type="submit" 
                                        class="px-2.5 py-1 text-gray-600 hover:bg-gray-200 font-bold"
                                        {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                    +
                                </button>
                            </form>
                        </div>

                        <!-- Thành tiền của dòng -->
                        <div class="text-right sm:min-w-[120px]">
                            <span class="block text-[11px] text-gray-400">Thành tiền</span>
                            <span class="font-black text-gray-900 text-sm sm:text-base">
                                {{ number_format($item->line_total, 0, ',', '.') }} đ
                            </span>
                        </div>

                        <!-- Nút xóa sản phẩm -->
                        <form method="POST" action="{{ route('cart.destroy', $item->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ?')"
                                    class="text-gray-400 hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition" 
                                    title="Xóa sản phẩm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <span class="text-5xl block mb-3">🛒</span>
                    <p class="text-gray-500 text-base mb-4">Giỏ hàng của bạn đang trống</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-5 py-2.5 bg-red-600 rounded-lg font-bold text-xs text-white uppercase tracking-wider hover:bg-red-700 transition">
                        Khám phá sản phẩm ngay &rarr;
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Cột phải: Hộp Tóm Tắt Tính Tiền (Tự động cập nhật theo sản phẩm ĐƯỢC CHỌN) -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl p-5 sm:p-6 h-fit border border-gray-200 sticky top-24">
        <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center justify-between">
            <span>Tóm tắt thanh toán</span>
            <span class="text-xs font-semibold text-red-600">
                (<span x-text="selectedCount"></span> máy được chọn)
            </span>
        </h3>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>Tạm tính (hàng chọn):</span>
                <span class="font-bold text-gray-900" x-text="new Intl.NumberFormat('vi-VN').format(selectedSubtotal) + ' đ'"></span>
            </div>

            <div class="flex justify-between text-gray-600">
                <span>Phí vận chuyển:</span>
                <span class="text-emerald-600 font-bold">Miễn phí</span>
            </div>

            <div class="border-t border-gray-200 pt-3 mt-3 flex justify-between items-baseline">
                <span class="text-base font-bold text-gray-900">Tổng thanh toán:</span>
                <span class="text-xl font-black text-red-600" x-text="new Intl.NumberFormat('vi-VN').format(selectedSubtotal) + ' đ'"></span>
            </div>
            <p class="text-[11px] text-gray-400 italic text-right">(Đã bao gồm VAT & Freeship)</p>
        </div>

        <div class="mt-6 space-y-3">
            <template x-if="selectedCount > 0">
                <a :href="'{{ route('checkout.index') }}?selected_ids=' + selectedItems.map(i => i.id).join(',')" 
                   class="w-full flex items-center justify-center py-3.5 px-4 bg-red-600 hover:bg-red-700 text-white rounded-xl font-black text-sm uppercase tracking-wider shadow-lg shadow-red-600/30 transition">
                    Mua hàng (<span x-text="selectedCount"></span>)
                </a>
            </template>

            <template x-if="selectedCount === 0">
                <button disabled 
                        class="w-full py-3.5 px-4 bg-gray-200 text-gray-400 rounded-xl font-bold text-sm cursor-not-allowed">
                    Vui lòng chọn sản phẩm để mua
                </button>
            </template>

            <a href="{{ route('products.index') }}" 
               class="w-full flex items-center justify-center py-2.5 px-4 bg-gray-50 border border-gray-200 rounded-xl font-semibold text-xs text-gray-700 hover:bg-gray-100 transition">
                &larr; Tiếp tục chọn thêm máy ảnh
            </a>
        </div>

        <!-- Cam kết Kyma -->
        <div class="mt-6 pt-5 border-t border-gray-100 space-y-2 text-xs text-gray-500">
            <div class="flex items-center gap-2">
                <span class="text-emerald-500 font-bold">✓</span>
                <span>Cam kết chính hãng 100% Sony, Canon, Fuji</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-emerald-500 font-bold">✓</span>
                <span>Bảo hành chính hãng 12 - 24 tháng</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-emerald-500 font-bold">✓</span>
                <span>Hỗ trợ trả góp 0% lãi suất</span>
            </div>
        </div>
    </div>

</div>
