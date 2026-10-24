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
    paymentMethod: '{{ old('payment_method', 'cod') }}',
    selectedAddressId: '{{ old('address_id', $addresses->firstWhere('is_default', true)?->id ?? $addresses->first()?->id ?? '') }}',
    receiverName: '{{ addslashes(old('receiver_name', auth()->user()->name ?? '')) }}',
    phone: '{{ addslashes(old('phone', auth()->user()->phone ?? '')) }}',
    customAddress: '{{ addslashes(old('shipping_address', '')) }}',
    orderNote: '{{ addslashes(old('note', '')) }}',
    couponInput: '',
    appliedCoupon: null,
    couponMessage: '',
    couponError: '',
    isApplyingCoupon: false,
    isSubmitting: false,
    
    get selectedItems() {
        return this.items.filter(i => i.selected);
    },
    get selectedCount() {
        return this.selectedItems.reduce((sum, i) => sum + i.quantity, 0);
    },
    get selectedSubtotal() {
        return this.selectedItems.reduce((sum, i) => sum + (i.price * i.quantity), 0);
    },
    get discountAmount() {
        if (!this.appliedCoupon) return 0;
        if (this.appliedCoupon.type === 'percent') {
            return Math.round((this.selectedSubtotal * this.appliedCoupon.value) / 100);
        }
        return Math.min(this.appliedCoupon.value, this.selectedSubtotal);
    },
    get totalPayment() {
        return Math.max(0, this.selectedSubtotal - this.discountAmount);
    },
    async applyCoupon() {
        if (!this.couponInput.trim()) {
            this.couponError = 'Vui lòng nhập mã giảm giá';
            this.couponMessage = '';
            return;
        }
        if (this.selectedSubtotal <= 0) {
            this.couponError = 'Vui lòng chọn sản phẩm trước khi áp dụng mã';
            this.couponMessage = '';
            return;
        }

        this.isApplyingCoupon = true;
        this.couponError = '';
        this.couponMessage = '';

        try {
            const res = await fetch('{{ route('cart.coupon.apply') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: this.couponInput.trim(),
                    subtotal: this.selectedSubtotal
                })
            });

            const data = await res.json();
            if (!res.ok || !data.success) {
                this.couponError = data.message || 'Mã giảm giá không hợp lệ';
                this.appliedCoupon = null;
            } else {
                this.appliedCoupon = data.coupon;
                this.appliedCoupon.discount = data.discount;
                this.couponMessage = data.message;
                this.couponInput = '';
            }
        } catch (e) {
            this.couponError = 'Không thể kết nối máy chủ, vui lòng thử lại';
        } finally {
            this.isApplyingCoupon = false;
        }
    },
    removeCoupon() {
        this.appliedCoupon = null;
        this.couponMessage = '';
        this.couponError = '';
        this.couponInput = '';
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
                            <a href="{{ route('products.show', $item->product->id) }}" class="font-heading font-semibold tracking-tight !text-black hover:underline transition text-sm sm:text-[16px] line-clamp-2" style="color: #000000 !important;">
                                {{ $item->product->name }}
                            </a>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-ink/70">SKU: {{ $item->product->sku ?? 'N/A' }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $item->product->stock > 0 ? 'bg-success text-success-ink font-semibold' : 'bg-danger text-danger-ink font-bold' }}">
                                    {{ $item->product->stock > 0 ? 'Còn ' . $item->product->stock . ' máy' : 'Hết hàng' }}
                                </span>
                            </div>
                            <p class="font-heading text-sm font-bold tabular-nums !text-black mt-1" style="color: #000000 !important;">
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
                            <span class="font-heading font-bold tabular-nums !text-black text-sm sm:text-[16px]" style="color: #000000 !important;">
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

    <!-- Cột phải: FORM ĐẶT HÀNG TRỰC TIẾP TẠI GIỎ HÀNG -->
    <form method="POST" 
          action="{{ route('checkout.store') }}" 
          class="bg-surface overflow-hidden shadow-sm rounded-2xl p-5 sm:p-6 h-fit border border-ink/10 sticky top-24 space-y-6"
          id="cart-checkout-form"
          @submit="
              if (selectedCount === 0) {
                  $event.preventDefault();
                  alert('Vui lòng chọn ít nhất một sản phẩm để đặt hàng!');
                  return;
              }
              if ((selectedAddressId === 'new' || {{ $addresses->isEmpty() ? 'true' : 'false' }}) && (!receiverName.trim() || !phone.trim() || !customAddress.trim())) {
                  $event.preventDefault();
                  alert('Vui lòng nhập đầy đủ họ tên, số điện thoại và địa chỉ giao hàng!');
                  return;
              }
              if (selectedAddressId !== 'new' && !selectedAddressId && {{ $addresses->isNotEmpty() ? 'true' : 'false' }}) {
                  $event.preventDefault();
                  alert('Vui lòng chọn một địa chỉ nhận hàng!');
                  return;
              }
              isSubmitting = true;
          ">
        @csrf

        <!-- Danh sách ID sản phẩm được chọn để mua -->
        <template x-for="item in selectedItems" :key="item.id">
            <input type="hidden" name="selected_ids[]" :value="item.id">
        </template>
        
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
            <div class="flex items-center justify-between mb-2">
                <label class="block font-heading text-xs font-semibold uppercase tracking-tight text-ink flex items-center gap-1.5">
                    <x-phosphor-map-pin-light class="w-4 h-4 text-ink inline-block" /> ĐỊA CHỈ NHẬN HÀNG:
                </label>
                <a href="{{ route('profile.edit') }}" class="text-[11px] text-primary hover:underline font-semibold" title="Quản lý sổ địa chỉ cá nhân">
                    + Quản lý sổ địa chỉ
                </a>
            </div>

            <input type="hidden" name="address_id" :value="selectedAddressId !== 'new' ? selectedAddressId : ''">

            @if ($addresses->isNotEmpty())
                <div class="space-y-2">
                    @foreach ($addresses as $addr)
                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl border transition cursor-pointer text-xs"
                               :class="selectedAddressId == '{{ $addr->id }}' ? 'border-primary bg-base text-ink shadow-xs ring-1 ring-primary' : 'border-ink/10 bg-base/60 hover:border-ink/20 text-ink/70'">
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
                                    {{ $addr->detail }}, {{ $addr->ward }}{{ $addr->district ? ', ' . $addr->district : '' }}, {{ $addr->province }}
                                </p>
                            </div>
                        </label>
                    @endforeach

                    <!-- Tùy chọn giao đến địa chỉ khác -->
                    <label class="flex items-center gap-2.5 p-2.5 rounded-xl border transition cursor-pointer text-xs"
                           :class="selectedAddressId === 'new' ? 'border-primary bg-base text-ink shadow-xs ring-1 ring-primary' : 'border-ink/10 bg-base/60 hover:border-ink/20 text-ink/70'">
                        <input type="radio" 
                               name="address_choice" 
                               value="new" 
                               x-model="selectedAddressId"
                               class="text-primary focus:ring-primary">
                        <span class="font-medium text-ink">Giao đến địa chỉ khác...</span>
                    </label>
                </div>
            @endif

            <!-- Khối nhập địa chỉ trực tiếp (khi chọn 'new' hoặc chưa có địa chỉ nào) -->
            <div x-show="selectedAddressId === 'new' || {{ $addresses->isEmpty() ? 'true' : 'false' }}" 
                 x-cloak
                 class="space-y-2 mt-3 p-3 bg-base/70 rounded-xl border border-ink/10">
                <p class="text-[11px] font-bold text-ink mb-1">Nhập thông tin người nhận hàng:</p>
                <div>
                    <input type="text" 
                           name="receiver_name" 
                           x-model="receiverName" 
                           :disabled="selectedAddressId !== 'new' && {{ $addresses->isNotEmpty() ? 'true' : 'false' }}"
                           placeholder="Họ và tên người nhận *" 
                           class="w-full text-xs rounded-lg bg-surface border-ink/20 focus:border-primary focus:ring-primary p-2 text-ink">
                </div>
                <div>
                    <input type="text" 
                           name="phone" 
                           x-model="phone" 
                           :disabled="selectedAddressId !== 'new' && {{ $addresses->isNotEmpty() ? 'true' : 'false' }}"
                           placeholder="Số điện thoại liên hệ *" 
                           class="w-full text-xs rounded-lg bg-surface border-ink/20 focus:border-primary focus:ring-primary p-2 text-ink">
                </div>
                <div>
                    <textarea name="shipping_address" 
                              x-model="customAddress" 
                              :disabled="selectedAddressId !== 'new' && {{ $addresses->isNotEmpty() ? 'true' : 'false' }}"
                              rows="2" 
                              placeholder="Địa chỉ cụ thể: Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành... *" 
                              class="w-full text-xs rounded-lg bg-surface border-ink/20 focus:border-primary focus:ring-primary p-2 text-ink"></textarea>
                </div>
            </div>
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

        <!-- 4. Ghi chú đơn hàng -->
        <div>
            <label class="block font-heading text-xs font-semibold uppercase tracking-tight text-ink mb-1.5 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-ink inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                GHI CHÚ ĐƠN HÀNG:
            </label>
            <textarea name="note" 
                      x-model="orderNote" 
                      rows="2" 
                      placeholder="Ghi chú thêm cho shipper hoặc người bán (không bắt buộc)..." 
                      class="w-full text-xs rounded-xl bg-base border-ink/20 focus:border-primary focus:ring-primary p-2.5 text-ink"></textarea>
        </div>

        <!-- 5. MÃ GIẢM GIÁ / VOUCHER -->
        <div class="border-t border-ink/10 pt-4">
            <input type="hidden" name="coupon_code" :value="appliedCoupon ? appliedCoupon.code : ''">
            <label class="block font-heading text-xs font-semibold uppercase tracking-tight text-ink mb-2 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-ink inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                MÃ GIẢM GIÁ / VOUCHER:
            </label>

            <!-- Chưa áp dụng voucher -->
            <template x-if="!appliedCoupon">
                <div>
                    <div class="flex gap-2">
                        <input type="text" 
                               x-model="couponInput" 
                               @keydown.enter.prevent="applyCoupon()"
                               placeholder="Nhập mã (VD: WELCOME10, GIAM500K)..." 
                               class="flex-1 uppercase font-bold text-xs rounded-xl bg-base border-ink/20 focus:border-primary focus:ring-primary p-2.5 text-ink">
                        <button type="button" 
                                @click="applyCoupon()" 
                                :disabled="isApplyingCoupon || !couponInput.trim()"
                                class="px-3.5 py-2 bg-ink text-cream font-bold text-xs rounded-xl hover:bg-ink/80 transition disabled:opacity-50 disabled:cursor-not-allowed shrink-0 shadow-xs">
                            <span x-show="!isApplyingCoupon">Áp dụng</span>
                            <span x-show="isApplyingCoupon" x-cloak>Đang kiểm tra...</span>
                        </button>
                    </div>

                    <!-- Gợi ý mã có sẵn trong hệ thống -->
                    <div class="mt-2 flex flex-wrap gap-1.5 items-center">
                        <span class="text-[11px] text-ink/60">Gợi ý mã:</span>
                        <button type="button" 
                                @click="couponInput = 'WELCOME10'; applyCoupon();" 
                                class="text-[11px] px-2 py-0.5 rounded-lg bg-base border border-ink/15 text-ink hover:border-primary hover:text-primary transition font-mono font-bold">
                            WELCOME10 (-10%)
                        </button>
                        <button type="button" 
                                @click="couponInput = 'GIAM500K'; applyCoupon();" 
                                class="text-[11px] px-2 py-0.5 rounded-lg bg-base border border-ink/15 text-ink hover:border-primary hover:text-primary transition font-mono font-bold">
                            GIAM500K (-500k)
                        </button>
                    </div>
                </div>
            </template>

            <!-- Đã áp dụng voucher thành công -->
            <template x-if="appliedCoupon">
                <div class="p-2.5 rounded-xl bg-success/20 border border-success-ink/30 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded font-mono font-bold text-xs bg-success text-success-ink uppercase shadow-2xs" x-text="appliedCoupon.code"></span>
                        <span class="text-xs font-semibold text-success-ink" x-text="appliedCoupon.type === 'percent' ? 'Giảm ' + appliedCoupon.value + '%' : 'Giảm ' + new Intl.NumberFormat('vi-VN').format(appliedCoupon.value) + ' đ'"></span>
                    </div>
                    <button type="button" @click="removeCoupon()" class="text-xs text-danger-ink hover:underline font-bold px-1.5 py-0.5" title="Hủy áp dụng mã">
                        Hủy mã
                    </button>
                </div>
            </template>

            <!-- Thông báo thành công / lỗi -->
            <p x-show="couponMessage" x-text="couponMessage" class="mt-1.5 text-xs text-success-ink font-semibold" x-cloak></p>
            <p x-show="couponError" x-text="couponError" class="mt-1.5 text-xs text-danger-ink font-semibold" x-cloak></p>

            <!-- Ghi chú quy định voucher -->
            <p class="text-[11px] text-ink/60 mt-2 flex items-center gap-1 italic">
                <svg class="w-3.5 h-3.5 text-ink/50 inline-block shrink-0 not-italic" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Mỗi đơn hàng chỉ áp dụng 1 mã giảm giá</span>
            </p>
        </div>

        <!-- 6. Chi tiết Bảng tính tiền & Thuế VAT -->
        <div class="bg-base p-4 rounded-xl space-y-2.5 text-xs text-ink/70 border border-ink/10">
            <!-- Tạm tính -->
            <div class="flex justify-between items-center">
                <span>Tạm tính (hàng chọn):</span>
                <span class="font-heading font-bold tabular-nums text-ink text-sm" x-text="new Intl.NumberFormat('vi-VN').format(selectedSubtotal) + ' đ'"></span>
            </div>

            <!-- Giảm giá voucher (chỉ hiện khi có voucher áp dụng) -->
            <template x-if="discountAmount > 0">
                <div class="flex justify-between items-center text-success-ink font-semibold">
                    <span class="flex items-center gap-1">
                        Giảm giá voucher:
                        <span class="text-[10px] bg-success/50 px-1.5 py-0.5 rounded font-bold font-mono" x-text="appliedCoupon ? appliedCoupon.code : ''"></span>
                    </span>
                    <span class="font-heading font-bold tabular-nums" x-text="'- ' + new Intl.NumberFormat('vi-VN').format(discountAmount) + ' đ'"></span>
                </div>
            </template>

            <!-- Thuế VAT (Đã bao gồm trong giá máy ảnh) -->
            <div class="flex justify-between items-center text-ink/70">
                <span class="flex items-center gap-1">
                    Thuế VAT (10%):
                    <span class="text-[10px] bg-surface text-ink px-1 rounded font-bold border border-ink/10">Quy định</span>
                </span>
                <span class="font-medium text-ink">Đã bao gồm trong giá</span>
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
                    <span class="text-xs text-ink/70 italic">(Miễn phí vận chuyển)</span>
                </div>
                <span class="font-heading text-lg sm:text-xl font-bold tabular-nums text-primary" x-text="new Intl.NumberFormat('vi-VN').format(totalPayment) + ' đ'"></span>
            </div>
        </div>

        <!-- 7. Nút Đặt Hàng Trực Tiếp -->
        <div class="space-y-2.5">
            <template x-if="selectedCount > 0">
                <x-button variant="primary" size="lg" type="submit" ::disabled="isSubmitting" class="w-full uppercase tracking-wider font-bold shadow-sm">
                    <span x-show="!isSubmitting">Đặt hàng ngay (<span x-text="selectedCount"></span>)</span>
                    <span x-show="isSubmitting" x-cloak class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        Đang đặt hàng...
                    </span>
                </x-button>
            </template>

            <template x-if="selectedCount === 0">
                <x-button variant="secondary" size="lg" type="button" disabled class="w-full">
                    Vui lòng chọn sản phẩm để đặt hàng
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
    </form>

</div>
