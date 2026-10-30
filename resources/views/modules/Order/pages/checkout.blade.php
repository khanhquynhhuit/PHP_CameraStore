<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-semibold tracking-tight text-xl text-ink leading-tight">
            {{ __('Thanh toán đơn hàng') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        provinces: [],
        wards: [],
        selectedProvinceCode: '',
        selectedWardCode: '',
        provinceName: '',
        wardName: '',
        streetInput: '',
        isLoadingProvinces: false,
        isLoadingWards: false,
        shippingAddress: '{{ addslashes(old('shipping_address', $formattedAddress ?? '')) }}',
        couponCode: '{{ addslashes(old('coupon_code', request()->query('coupon_code', ''))) }}',

        async init() {
            await this.loadProvinces();
        },

        async loadProvinces() {
            this.isLoadingProvinces = true;
            try {
                const res = await fetch('https://provinces.open-api.vn/api/v2/p/');
                if (!res.ok) throw new Error('Không thể tải danh sách Tỉnh/Thành');
                this.provinces = await res.json();
            } catch (e) {
                console.warn('Lỗi gọi API Tỉnh/Thành phố:', e);
            } finally {
                this.isLoadingProvinces = false;
            }
        },

        async onProvinceChange() {
            const found = this.provinces.find(p => String(p.code) === String(this.selectedProvinceCode));
            this.provinceName = found ? found.name : '';
            this.wards = [];
            this.selectedWardCode = '';
            this.wardName = '';
            this.updateFullAddress();

            if (this.selectedProvinceCode) {
                await this.loadWards(this.selectedProvinceCode);
            }
        },

        async loadWards(provCode) {
            this.isLoadingWards = true;
            try {
                const res = await fetch(`https://provinces.open-api.vn/api/v2/p/${provCode}?depth=2`);
                if (!res.ok) throw new Error('Không thể tải danh sách Phường/Xã');
                const data = await res.json();
                this.wards = data.wards || [];
            } catch (e) {
                console.warn('Lỗi gọi API Phường/Xã:', e);
            } finally {
                this.isLoadingWards = false;
            }
        },

        onWardChange() {
            const found = this.wards.find(w => String(w.code) === String(this.selectedWardCode));
            this.wardName = found ? found.name : '';
            this.updateFullAddress();
        },

        updateFullAddress() {
            let parts = [];
            if (this.streetInput.trim()) parts.push(this.streetInput.trim());
            if (this.wardName.trim()) parts.push(this.wardName.trim());
            if (this.provinceName.trim()) parts.push(this.provinceName.trim());
            if (parts.length > 0) {
                this.shippingAddress = parts.join(', ');
            }
        }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface border border-ink/10 overflow-hidden shadow-sm sm:rounded-xl p-6">
                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf

                    <h3 class="font-heading text-lg font-semibold tracking-tight text-ink mb-4">Thông tin giao hàng</h3>

                    <x-form-input 
                        name="receiver_name" 
                        label="Họ và tên người nhận" 
                        :value="old('receiver_name', $receiverName ?? auth()->user()->name)" 
                        placeholder="VD: Nguyễn Văn Khách" 
                        required 
                    />

                    <x-form-input 
                        name="phone" 
                        label="Số điện thoại liên hệ" 
                        :value="old('phone', $phone ?? auth()->user()->phone)" 
                        maxlength="10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        placeholder="VD: 0903456789" 
                        required 
                    />

                    <!-- ============ KHỐI CHỌN ĐỊA CHỈ TỪ API TỈNH/PHƯỜNG (COMBO BOX) ============ -->
                    <div class="mb-4 p-4 bg-sand/30 border border-ink/10 rounded-xl space-y-3">
                        <label class="block font-heading text-xs font-semibold uppercase tracking-tight text-ink flex items-center gap-1.5">
                            <x-phosphor-map-pin-light class="w-4 h-4 text-ink inline-block" />
                            Chọn khu vực nhận hàng (API Tỉnh / Phường/Xã)
                        </label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label for="checkout_select_province" class="block font-medium text-xs text-ink mb-1">
                                    Tỉnh / Thành phố <span class="text-danger-ink font-bold">*</span>
                                    <span x-show="isLoadingProvinces" class="text-[10px] text-primary animate-pulse ml-1">(Đang tải...)</span>
                                </label>
                                <select id="checkout_select_province"
                                        x-model="selectedProvinceCode" 
                                        @change="onProvinceChange()" 
                                        :disabled="isLoadingProvinces"
                                        class="border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm block w-full text-xs py-2 px-3 cursor-pointer disabled:opacity-50">
                                    <option value="">-- Chọn Tỉnh / Thành phố --</option>
                                    <template x-for="p in provinces" :key="p.code">
                                        <option :value="p.code" x-text="p.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label for="checkout_select_ward" class="block font-medium text-xs text-ink mb-1">
                                    Phường / Xã <span class="text-danger-ink font-bold">*</span>
                                    <span x-show="isLoadingWards" class="text-[10px] text-primary animate-pulse ml-1">(Đang tải...)</span>
                                </label>
                                <select id="checkout_select_ward"
                                        x-model="selectedWardCode" 
                                        @change="onWardChange()" 
                                        :disabled="!selectedProvinceCode || isLoadingWards"
                                        class="border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm block w-full text-xs py-2 px-3 cursor-pointer disabled:opacity-50 disabled:bg-sand/30">
                                    <option value="" x-text="!selectedProvinceCode ? '-- Chọn Tỉnh trước --' : (isLoadingWards ? '-- Đang tải... --' : '-- Chọn Phường / Xã --')"></option>
                                    <template x-for="w in wards" :key="w.code">
                                        <option :value="w.code" x-text="w.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="checkout_street" class="block font-medium text-xs text-ink mb-1">Số nhà, tên đường</label>
                            <input type="text" 
                                   id="checkout_street"
                                   x-model="streetInput" 
                                   @input="updateFullAddress()"
                                   placeholder="VD: Số 144 Xuân Thủy" 
                                   class="border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm block w-full text-xs py-2 px-3">
                        </div>
                    </div>
                    <!-- ========================================================================== -->

                    <!-- ĐỊA CHỈ HOÀN CHỈNH -->
                    <div class="mb-4">
                        <label for="shipping_address" class="block font-medium text-sm text-ink mb-1">
                            Địa chỉ nhận hàng hoàn chỉnh <span class="text-danger-ink font-bold">*</span>
                        </label>
                        <input type="text" 
                               id="shipping_address"
                               name="shipping_address" 
                               x-model="shippingAddress"
                               placeholder="Vui lòng chọn Tỉnh, Phường/Xã và nhập số nhà ở trên..." 
                               required 
                               class="border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm block w-full text-sm py-2 px-3">
                        <x-input-error for="shipping_address" />
                    </div>

                    <x-form-select 
                        name="payment_method" 
                        label="Phương thức thanh toán" 
                        :selected="old('payment_method', $paymentMethod ?? 'cod')" 
                        :options="[
                            'cod' => 'Thanh toán khi nhận hàng (COD)',
                            'bank' => 'Chuyển khoản ngân hàng (VietQR / Banking)',
                            'vnpay' => 'Ví điện tử VNPAY QR'
                        ]" 
                        required 
                    />

                    <!-- MÃ GIẢM GIÁ & GỢI Ý VOUCHER -->
                    <div class="mb-4">
                        <label for="checkout_coupon_code" class="block font-medium text-sm text-ink mb-1">
                            Mã giảm giá (nếu có)
                        </label>
                        <input type="text"
                               id="checkout_coupon_code"
                               name="coupon_code"
                               x-model="couponCode"
                               placeholder="VD: WELCOME10, GIAM500K"
                               class="uppercase font-bold border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm block w-full text-sm py-2 px-3">
                        
                        <!-- Gợi ý voucher có sẵn -->
                        <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                            <span class="text-[11px] text-ink/60 font-medium">Gợi ý voucher:</span>
                            @if (isset($coupons) && $coupons->isNotEmpty())
                                @foreach ($coupons as $cp)
                                    <button type="button" 
                                            @click="couponCode = '{{ $cp->code }}'" 
                                            class="text-[11px] px-2 py-0.5 rounded-lg bg-base border border-ink/15 text-ink hover:border-primary hover:text-primary transition font-mono font-bold"
                                            title="Đơn tối thiểu: {{ number_format($cp->min_order, 0, ',', '.') }} đ">
                                        {{ $cp->code }} ({{ $cp->type->value === 'percent' ? '-' . (int)$cp->value . '%' : '-' . number_format($cp->value / 1000, 0) . 'k' }})
                                    </button>
                                @endforeach
                            @else
                                <button type="button" 
                                        @click="couponCode = 'WELCOME10'" 
                                        class="text-[11px] px-2 py-0.5 rounded-lg bg-base border border-ink/15 text-ink hover:border-primary hover:text-primary transition font-mono font-bold">
                                    WELCOME10 (-10%)
                                </button>
                                <button type="button" 
                                        @click="couponCode = 'GIAM500K'" 
                                        class="text-[11px] px-2 py-0.5 rounded-lg bg-base border border-ink/15 text-ink hover:border-primary hover:text-primary transition font-mono font-bold">
                                    GIAM500K (-500k)
                                </button>
                            @endif
                        </div>
                        <p class="text-xs text-ink/60 mb-2 italic">* Mỗi đơn hàng chỉ áp dụng 1 mã giảm giá</p>
                        <x-input-error for="coupon_code" />
                    </div>

                    <x-form-textarea 
                        name="note" 
                        label="Ghi chú đơn hàng" 
                        rows="2" 
                        placeholder="Ghi chú cho shipper (không bắt buộc)" 
                    />

                    <div class="mt-6 flex items-center justify-between gap-4">
                        <x-button variant="ghost" size="md" :href="route('cart.index')">Quay lại giỏ hàng</x-button>
                        <x-button variant="primary" size="lg" type="submit">{{ __('Xác nhận đặt hàng') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
