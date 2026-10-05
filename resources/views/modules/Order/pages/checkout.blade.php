<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thanh toán đơn hàng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin giao hàng</h3>
                    <x-form-input name="shipping_address" label="Địa chỉ nhận hàng" placeholder="VD: Số nhà, Tên đường, Phường/Xã, Quận/Huyện, TP..." required />
                    <x-form-input name="phone" label="Số điện thoại liên hệ" placeholder="VD: 0901234567" required />
                    <x-form-select name="payment_method" label="Phương thức thanh toán" :options="['cod' => 'Thanh toán khi nhận hàng (COD)', 'vnpay' => 'VNPay QR', 'credit_card' => 'Thẻ tín dụng / Ghi nợ']" required />
                    <x-form-textarea name="note" label="Ghi chú đơn hàng" rows="2" placeholder="Ghi chú cho shipper (không bắt buộc)" />

                    <div class="mt-6 flex items-center justify-between">
                        <a href="{{ route('cart.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Quay lại giỏ hàng</a>
                        <x-primary-button>{{ __('Xác nhận đặt hàng') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
