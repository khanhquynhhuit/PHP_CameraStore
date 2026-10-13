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
    paymentMethod: 'cod',
    selectedAddressId: '{{ $addresses->firstWhere('is_default', true)?->id ?? $addresses->first()?->id ?? '' }}',
    customAddress: '',
    vatRate: 0.10, // Thuế VAT 10%
    
    get selectedItems() {
        return this.items.filter(i => i.selected);
    },
    get selectedCount() {
        return this.selectedItems.reduce((sum, i) => sum + i.quantity, 0);
    },
    get selectedSubtotal() {
        return this.selectedItems.reduce((sum, i) => sum + (i.price * i.quantity), 0);
    },
    get vatAmount() {
        return Math.round(this.selectedSubtotal * this.vatRate);
    },
    get totalPayment() {
        return this.selectedSubtotal + this.vatAmount;
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
    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm rounded-2xl p-4 sm:p-6 border border-gray-200">
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

    <!-- Cột phải: BẢNG TÍNH TIỀN (VAT, ĐỊA CHỈ & PHƯƠNG THỨC THANH TOÁN) -->
    <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-5 sm:p-6 h-fit border border-gray-200 sticky top-24 space-y-6">
        
        <!-- 1. Tiêu đề tóm tắt -->
        <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
            <h3 class="text-base font-extrabold text-gray-900">
                Tóm tắt thanh toán
            </h3>
            <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">
                <span x-text="selectedCount"></span> máy được chọn
            </span>
        </div>

        <!-- 2. Chọn Địa chỉ giao hàng -->
        <div>
            <label class="block text-xs font-black uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                <span class="text-red-500">📍</span> ĐỊA CHỈ NHẬN HÀNG:
            </label>

            @if ($addresses->isNotEmpty())
                <div class="space-y-2">
                    @foreach ($addresses as $addr)
                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl border transition cursor-pointer text-xs"
                               :class="selectedAddressId == '{{ $addr->id }}' ? 'border-red-500 bg-red-50/50 text-gray-900' : 'border-gray-200 hover:border-gray-300 text-gray-600'">
                            <input type="radio" 
                                   name="address_choice" 
                                   value="{{ $addr->id }}" 
                                   x-model="selectedAddressId"
                                   class="mt-0.5 text-red-600 focus:ring-red-500">
                            <div class="min-w-0">
                                <div class="font-bold flex items-center gap-1.5">
                                    <span>{{ $addr->receiver_name }}</span>
                                    <span class="text-gray-400 font-normal">({{ $addr->phone }})</span>
                                    @if ($addr->is_default)
                                        <span class="text-[10px] bg-red-100 text-red-700 px-1.5 rounded font-bold">Mặc định</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2">
                                    {{ $addr->detail }}, {{ $addr->ward }}, {{ $addr->district }}, {{ $addr->province }}
                                </p>
                            </div>
                        </label>
                    @endforeach
                </div>
            @else
                <!-- Nhập địa chỉ trực tiếp nếu chưa lưu -->
                <textarea x-model="customAddress" 
                          rows="2" 
                          placeholder="Nhập số nhà, tên đường, phường/xã, quận/huyện, tỉnh thành..." 
                          class="w-full text-xs rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 p-2.5"></textarea>
            @endif
        </div>

        <!-- 3. Chọn Phương thức thanh toán (Tiền mặt / Chuyển khoản) -->
        <div>
            <label class="block text-xs font-black uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                <span class="text-blue-500">💳</span> PHƯƠNG THỨC THANH TOÁN:
            </label>

            <div class="grid grid-cols-2 gap-2 text-xs">
                <!-- Tiền mặt COD -->
                <label class="p-3 rounded-xl border flex flex-col justify-between cursor-pointer transition select-none"
                       :class="paymentMethod === 'cod' ? 'border-red-600 bg-red-50/60 ring-1 ring-red-500' : 'border-gray-200 hover:border-gray-300'">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-lg">💵</span>
                        <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="text-red-600 focus:ring-red-500">
                    </div>
                    <span class="font-extrabold text-gray-900 leading-tight">Tiền mặt (COD)</span>
                    <span class="text-[10px] text-gray-500 mt-0.5">Khi nhận hàng</span>
                </label>

                <!-- Chuyển khoản Ngân hàng -->
                <label class="p-3 rounded-xl border flex flex-col justify-between cursor-pointer transition select-none"
                       :class="paymentMethod === 'bank' ? 'border-red-600 bg-red-50/60 ring-1 ring-red-500' : 'border-gray-200 hover:border-gray-300'">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-lg">🏦</span>
                        <input type="radio" name="payment_method" value="bank" x-model="paymentMethod" class="text-red-600 focus:ring-red-500">
                    </div>
                    <span class="font-extrabold text-gray-900 leading-tight">Chuyển khoản</span>
                    <span class="text-[10px] text-gray-500 mt-0.5">VietQR / Banking</span>
                </label>
            </div>
        </div>

        <!-- 4. Chi tiết Bảng tính tiền & Thuế VAT -->
        <div class="bg-gray-50 p-4 rounded-xl space-y-2.5 text-xs text-gray-600 border border-gray-100">
            <!-- Tạm tính -->
            <div class="flex justify-between items-center">
                <span>Tạm tính (hàng chọn):</span>
                <span class="font-bold text-gray-900 text-sm" x-text="new Intl.NumberFormat('vi-VN').format(selectedSubtotal) + ' đ'"></span>
            </div>

            <!-- Thuế VAT 10% (Hiển thị rõ ràng bao nhiêu tiền) -->
            <div class="flex justify-between items-center text-gray-700">
                <span class="flex items-center gap-1">
                    Thuế VAT (10%):
                    <span class="text-[10px] bg-gray-200 text-gray-700 px-1 rounded font-bold">Quy định</span>
                </span>
                <span class="font-bold text-gray-900" x-text="'+ ' + new Intl.NumberFormat('vi-VN').format(vatAmount) + ' đ'"></span>
            </div>

            <!-- Phí ship -->
            <div class="flex justify-between items-center">
                <span>Phí vận chuyển:</span>
                <span class="text-emerald-600 font-extrabold">Miễn phí (0 đ)</span>
            </div>

            <!-- Tổng thanh toán cuối cùng -->
            <div class="border-t border-gray-200 pt-3 mt-2 flex justify-between items-baseline">
                <div>
                    <span class="text-xs font-extrabold text-gray-900 block">TỔNG THANH TOÁN:</span>
                    <span class="text-[10px] text-gray-400 italic">(Đã cộng VAT 10% & Freeship)</span>
                </div>
                <span class="text-lg sm:text-xl font-black text-red-600" x-text="new Intl.NumberFormat('vi-VN').format(totalPayment) + ' đ'"></span>
            </div>
        </div>

        <!-- 5. Nút Mua Hàng -->
        <div class="space-y-2.5">
            <template x-if="selectedCount > 0">
                <a :href="'{{ route('checkout.index') }}?selected_ids=' + selectedItems.map(i => i.id).join(',') + '&payment_method=' + paymentMethod + '&address_id=' + selectedAddressId" 
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
        <div class="pt-4 border-t border-gray-100 space-y-2 text-xs text-gray-500">
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
                <span>Xuất hóa đơn VAT điện tử đầy đủ</span>
            </div>
        </div>
    </div>

</div>
