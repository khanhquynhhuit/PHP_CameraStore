<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Giỏ hàng của bạn') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Sản phẩm đã chọn</h3>
                    @include('modules.Cart.components.cart-item')
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                    @include('modules.Cart.components.cart-summary')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('modules.Cart.services.cart-service')
    @endpush
</x-app-layout>
