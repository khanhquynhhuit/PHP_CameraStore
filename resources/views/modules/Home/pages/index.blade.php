<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('modules.Home.components.hero-banner')

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Chào mừng đến với Camera Store</h2>
                    <a href="{{ route('products.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                        Xem tất cả sản phẩm &rarr;
                    </a>
                </div>
                <p class="text-gray-600 text-sm">
                    Hệ thống cửa hàng máy ảnh, ống kính và phụ kiện nhiếp ảnh chính hãng.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
