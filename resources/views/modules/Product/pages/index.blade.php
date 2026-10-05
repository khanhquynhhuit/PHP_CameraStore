<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Danh sách sản phẩm') }}
            </h2>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                + Thêm sản phẩm
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @include('modules.Product.components.product-filter')
                
                {{-- TODO: Render danh sách sản phẩm --}}
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    {{-- @forelse ($products as $product)
                        @include('modules.Product.components.product-card', ['product' => $product])
                    @empty
                        <p class="col-span-full text-center text-gray-500 py-8">Chưa có sản phẩm nào.</p>
                    @endforelse --}}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('modules.Product.services.product-service')
    @endpush
</x-app-layout>
