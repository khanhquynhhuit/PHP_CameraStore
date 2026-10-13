<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-heading font-semibold tracking-tight text-xl text-ink leading-tight">
                {{ __('Danh sách sản phẩm') }}
            </h2>
            <x-button variant="primary" size="sm" :href="route('admin.products.create')">
                + Thêm sản phẩm
            </x-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface border border-ink/10 overflow-hidden shadow-sm sm:rounded-xl p-6">
                @include('modules.Product.components.product-filter')
                
                {{-- TODO: Render danh sách sản phẩm --}}
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    {{-- @forelse ($products as $product)
                        @include('modules.Product.components.product-card', ['product' => $product])
                    @empty
                        <p class="col-span-full text-center text-slate-500 py-8">Chưa có sản phẩm nào.</p>
                    @endforelse --}}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('modules.Product.services.product-service')
    @endpush
</x-app-layout>
