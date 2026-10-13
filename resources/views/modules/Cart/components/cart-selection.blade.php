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
    <div class="lg:col-span-2 bg-surface overflow-hidden shadow-sm rounded-2xl p-4 sm:p-6 border border-ink/10">
        <!-- Thanh Chọn tất cả (Select All Header) -->
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-ink/10">
            <label class="flex items-center gap-3 cursor-pointer select-none">
                <input type="checkbox" 
                       x-model="selectAll" 
                       @change="toggleSelectAll()" 
                       class="w-4 h-4 text-primary rounded border-ink/20 focus:ring-primary">
                <span class="text-sm font-bold text-ink">
                    Chọn tất cả (<span x-text="items.length"></span> sản phẩm)
                </span>
            </label>

            <span class="text-xs text-ink/60">
                Đã chọn: <strong class="text-ink font-bold" x-text="selectedItems.length"></strong> món
            </span>
        </div>

        <!-- Danh sách từng sản phẩm -->
        <div class="divide-y divide-ink/10">
            @forelse ($items as $index => $item)
                <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <!-- Checkbox + Ảnh + Tên sản phẩm -->
                    <div class="flex items-center gap-3">
                        <!-- Checkbox chọn mua sản phẩm này -->
                        <input type="checkbox" 
                               x-model="items[{{ $index }}].selected" 
                               @change="updateSelectAllState()" 
                               class="w-4 h-4 text-primary rounded border-ink/20 focus:ring-primary shrink-0">

                        <a href="{{ route('products.show', $item->product->id) }}" class="shrink-0">
                            @php
                                $image = $item->product->primary_image;
                                $imageUrl = $image ? asset('storage/' . $image->image_url) : 'https://placehold.co/100x100?text=Camera';
                            @endphp
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-16 h-16 sm:w-20 sm:h-20 object-contain rounded-lg bg-base border border-ink/10 p-1"
                                 onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Camera';">
                        </a>

                        <div>
                            <a href="{{ route('products.show', $item->product->id) }}" class="font-heading font-semibold tracking-tight text-ink hover:underline transition text-sm sm:text-base line-clamp-2">
                                {{ $item->product->name }}
                            </a>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-ink/70">SKU: {{ $item->product->sku ?? 'N/A' }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $item->product->stock > 0 ? 'bg-success text-success-ink font-semibold' : 'bg-danger text-danger-ink font-bold' }}">
                                    {{ $item->product->stock > 0 ? 'Còn ' . $item->product->stock . ' máy' : 'Hết hàng' }}
                                </span>
                            </div>
                            <p class="font-heading text-sm font-bold tabular-nums text-ink mt-1">
                                {{ number_format($item->product->price, 0, ',', '.') }} đ
                            </p>
                        </div>
                    </div>

                    <!-- Tăng giảm số lượng và Nút Xóa -->
                    <div class="flex items-center justify-between sm:justify-end gap-4 border-t border-ink/10 sm:border-t-0 pt-2 sm:pt-0 pl-7 sm:pl-0">
                        <!-- Bộ chọn số lượng -->
                        <div class="flex items-center border border-ink/20 rounded-lg overflow-hidden bg-base">
                            <form method="POST" action="{{ route('cart.update', $item->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                <button type="submit" 
                                        class="px-2.5 py-1 text-ink hover:bg-surface font-bold"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    -
                                </button>
                            </form>

                            <span class="w-12 text-center text-sm font-bold tabular-nums text-ink py-1 bg-base border-x border-ink/10">
                                {{ $item->quantity }}
                            </span>

                            <form method="POST" action="{{ route('cart.update', $item->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                <button type="submit" 
                                        class="px-2.5 py-1 text-ink hover:bg-surface font-bold"
                                        {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                    +
                                </button>
                            </form>
                        </div>

                        <!-- Thành tiền của dòng -->
                        <div class="text-right sm:min-w-[120px]">
                            <span class="block text-xs text-ink/70">Thành tiền</span>
                            <span class="font-heading font-bold tabular-nums text-ink text-sm sm:text-base">
                                {{ number_format($item->line_total, 0, ',', '.') }} đ
                            </span>
                        </div>

                        <!-- Nút xóa sản phẩm -->
                        <form method="POST" action="{{ route('cart.destroy', $item->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ?')"
                                    class="text-ink/40 hover:text-danger-ink p-1.5 rounded-lg hover:bg-danger/20 transition" 
                                    title="Xóa sản phẩm">
                                <x-phosphor-trash-light class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <x-phosphor-shopping-cart-light class="w-16 h-16 text-ink/30 mx-auto mb-3" />
                    <p class="text-sm text-ink/70 mb-4">Giỏ hàng của bạn đang trống</p>
                    <x-button variant="primary" size="md" :href="route('products.index')" class="uppercase tracking-wider">
                        Khám phá sản phẩm ngay &rarr;
                    </x-button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Cột phải: BẢNG TÍNH TIỀN (VAT, ĐỊA CHỈ & PHƯƠNG THỨC THANH TOÁN) -->
    <div class="bg-surface overflow-hidden shadow-sm rounded-2xl p-5 sm:p-6 h-fit border border-ink/10 sticky top-24 space-y-6">
        
        <!-- 1. Tiêu đề tóm tắt -->
        <div class="border-b border-ink/10 pb-3 flex items-center justify-between">
            <h3 class="font-heading text-base font-semibold tracking-tight text-ink">
                Tóm tắt thanh toán
            </h3>
            <span class="text-xs font-bold tabular-nums text-cream bg-primary px-2.5 py-0.5 rounded-full">
                <span x-text="selectedCount"></span> máy được chọn
            </span>
        </div>

        <!-- 2. Chọn Địa chỉ giao hàng -->
        <div>
            <label class="block font-heading text-xs font-semibold uppercase tracking-tight text-ink mb-2 flex items-center gap-1.5">
                <x-phosphor-map-pin-light class="w-4 h-4 text-ink inline-block" /> ĐỊA CHỈ NHẬN HÀNG:
            </label>

            @if ($addresses->isNotEmpty())
                <div class="space-y-2">
                    @foreach ($addresses as $addr)
                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl border transition cursor-pointer text-xs"
                               :class="selectedAddressId == '{{ $addr->id }}' ? 'border-primary bg-base text-ink shadow-xs' : 'border-ink/10 bg-base/60 hover:border-ink/20 text-ink/70'">
                            <input type="radio" 
                                   name="address_choice" 
                                   value="{{ $addr->id }}" 
                                   x-model="selectedAddressId"
                                   class="mt-0.5 text-primary focus:ring-primary">
                            <div class="min-w-0">
                                <div class="font-bold flex items-center gap-1.5 text-ink">
                                    <span>{{ $addr->receiver_name }}</span>
                                    <span class="text-ink/70 font-normal">({{ $addr->phone }})</span>
                                    @if ($addr->is_default)
                                        <span class="text-[10px] bg-primary text-cream px-1.5 rounded font-semibold">Mặc định</span>
                                    @endif
                                </div>
                                <p class="text-xs text-ink/70 mt-0.5 line-clamp-2">
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
                          class="w-full text-xs rounded-xl bg-base border-ink/20 focus:border-primary focus:ring-primary p-2.5 text-ink"></textarea>
            @endif
        </div>

        <!-- 3. Chọn Phương thức thanh toán (Tiền mặt / Chuyển khoản) -->
        <div>
            <label class="block font-heading text-xs font-semibold uppercase tracking-tight text-ink mb-2 flex items-center gap-1.5">
                <x-phosphor-credit-card-light class="w-4 h-4 text-ink inline-block" /> PHƯƠNG THỨC THANH TOÁN:
            </label>

            <div class="grid grid-cols-2 gap-2 text-xs">
                <!-- Tiền mặt COD -->
                <label class="p-3 rounded-xl border flex flex-col justify-between cursor-pointer transition select-none"
                       :class="paymentMethod === 'cod' ? 'border-primary bg-base ring-1 ring-primary shadow-xs' : 'border-ink/10 bg-base/60 hover:border-ink/20'">
                    <div class="flex items-center justify-between mb-1">
                        <x-phosphor-money-light class="w-6 h-6 text-ink" />
                        <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="text-primary focus:ring-primary">
                    </div>
                    <span class="font-heading font-semibold text-ink leading-tight">Tiền mặt (COD)</span>
                    <span class="text-xs text-ink/70 mt-0.5">Khi nhận hàng</span>
                </label>

                <!-- Chuyển khoản Ngân hàng -->
                <label class="p-3 rounded-xl border flex flex-col justify-between cursor-pointer transition select-none"
                       :class="paymentMethod === 'bank' ? 'border-primary bg-base ring-1 ring-primary shadow-xs' : 'border-ink/10 bg-base/60 hover:border-ink/20'">
                    <div class="flex items-center justify-between mb-1">
                        <x-phosphor-bank-light class="w-6 h-6 text-ink" />
                        <input type="radio" name="payment_method" value="bank" x-model="paymentMethod" class="text-primary focus:ring-primary">
                    </div>
                    <span class="font-heading font-semibold text-ink leading-tight">Chuyển khoản</span>
                    <span class="text-xs text-ink/70 mt-0.5">VietQR / Banking</span>
                </label>
            </div>
        </div>

        <!-- 4. Chi tiết Bảng tính tiền & Thuế VAT -->
        <div class="bg-base p-4 rounded-xl space-y-2.5 text-xs text-ink/70 border border-ink/10">
            <!-- Tạm tính -->
            <div class="flex justify-between items-center">
                <span>Tạm tính (hàng chọn):</span>
                <span class="font-heading font-bold tabular-nums text-ink text-sm" x-text="new Intl.NumberFormat('vi-VN').format(selectedSubtotal) + ' đ'"></span>
            </div>

            <!-- Thuế VAT 10% (Hiển thị rõ ràng bao nhiêu tiền) -->
            <div class="flex justify-between items-center text-ink/70">
                <span class="flex items-center gap-1">
                    Thuế VAT (10%):
                    <span class="text-[10px] bg-surface text-ink px-1 rounded font-bold border border-ink/10">Quy định</span>
                </span>
                <span class="font-heading font-bold tabular-nums text-ink" x-text="'+ ' + new Intl.NumberFormat('vi-VN').format(vatAmount) + ' đ'"></span>
            </div>

            <!-- Phí ship -->
            <div class="flex justify-between items-center">
                <span>Phí vận chuyển:</span>
                <span class="font-heading text-success-ink font-bold tabular-nums">Miễn phí (0 đ)</span>
            </div>

            <!-- Tổng thanh toán cuối cùng -->
            <div class="border-t border-ink/10 pt-3 mt-2 flex justify-between items-baseline">
                <div>
                    <span class="font-heading text-xs font-semibold text-ink block">TỔNG THANH TOÁN:</span>
                    <span class="text-xs text-ink/70 italic">(Đã cộng VAT 10% & Freeship)</span>
                </div>
                <span class="font-heading text-lg sm:text-xl font-bold tabular-nums text-ink" x-text="new Intl.NumberFormat('vi-VN').format(totalPayment) + ' đ'"></span>
            </div>
        </div>

        <!-- 5. Nút Mua Hàng -->
        <div class="space-y-2.5">
            <template x-if="selectedCount > 0">
                <x-button variant="primary" size="lg" href="#" x-bind:href="'{{ route('checkout.index') }}?selected_ids=' + selectedItems.map(i => i.id).join(',') + '&payment_method=' + paymentMethod + '&address_id=' + selectedAddressId" class="w-full uppercase tracking-wider">
                    Mua hàng (<span x-text="selectedCount"></span>)
                </x-button>
            </template>

            <template x-if="selectedCount === 0">
                <x-button variant="secondary" size="lg" disabled class="w-full">
                    Vui lòng chọn sản phẩm để mua
                </x-button>
            </template>

            <x-button variant="outline" size="md" :href="route('products.index')" class="w-full">
                &larr; Tiếp tục chọn thêm máy ảnh
            </x-button>
        </div>

        <!-- Cam kết -->
        <div class="pt-4 border-t border-ink/10 space-y-2 text-xs text-ink/70">
            <div class="flex items-center gap-2">
                <x-phosphor-check-light class="w-4 h-4 text-success-ink shrink-0 font-bold" />
                <span>Cam kết chính hãng 100% Sony, Canon, Fuji</span>
            </div>
            <div class="flex items-center gap-2">
                <x-phosphor-check-light class="w-4 h-4 text-success-ink shrink-0 font-bold" />
                <span>Bảo hành chính hãng 12 - 24 tháng</span>
            </div>
            <div class="flex items-center gap-2">
                <x-phosphor-check-light class="w-4 h-4 text-success-ink shrink-0 font-bold" />
                <span>Xuất hóa đơn VAT điện tử đầy đủ</span>
            </div>
        </div>
    </div>

</div>
