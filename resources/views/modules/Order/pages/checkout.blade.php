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

                    <x-form-input 
                        name="shipping_address" 
                        label="Địa chỉ nhận hàng" 
                        :value="old('shipping_address', $formattedAddress ?? '')" 
                        placeholder="VD: Số 144 Xuân Thủy, Phường Dịch Vọng Hậu, Quận Cầu Giấy, Hà Nội" 
                        required 
                    />

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
