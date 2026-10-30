<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-semibold tracking-tight text-xl text-ink leading-tight">
            {{ __('Thanh toán đơn hàng') }}
        </h2>
    </x-slot>

    <div class="py-12">
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
                        placeholder="VD: 0903456789" 
                        required 
                    />

                    <!-- ============ ĐÃ THÊM: KHỐI CHỌN ĐỊA CHỈ TỪ API ============ -->
                    <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <label class="block font-medium text-sm text-ink mb-2">Chọn khu vực giao hàng</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                            <select id="api_province" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm w-full text-sm">
                                <option value="">Chọn Tỉnh/Thành</option>
                            </select>
                            <select id="api_district" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm w-full text-sm" disabled>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                            <select id="api_ward" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm w-full text-sm" disabled>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-ink mb-1">Số nhà, tên đường</label>
                            <input type="text" id="api_street" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm w-full text-sm" placeholder="VD: Số 144 Xuân Thủy" />
                        </div>
                    </div>
                    <!-- ========================================================= -->

                    <!-- GIỮ NGUYÊN FIELD CŨ ĐỂ KHÔNG LỖI BACKEND, NHƯNG KHÓA READONLY ĐỂ TỰ ĐỘNG ĐIỀN TỪ API -->
                    <div class="mb-4">
                        <x-form-input 
                            name="shipping_address" 
                            label="Địa chỉ hoàn chỉnh (Hệ thống tự động điền)" 
                            :value="old('shipping_address', $formattedAddress ?? '')" 
                            placeholder="Vui lòng chọn khu vực và nhập số nhà ở trên..." 
                            readonly
                            required 
                        />
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

                    <x-form-input 
                        name="coupon_code" 
                        label="Mã giảm giá (nếu có)" 
                        :value="old('coupon_code', request()->query('coupon_code', ''))" 
                        placeholder="VD: WELCOME10, GIAM500K" 
                    />
                    <p class="text-xs text-ink/60 -mt-2 mb-4 italic">* Mỗi đơn hàng chỉ áp dụng 1 mã giảm giá</p>

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