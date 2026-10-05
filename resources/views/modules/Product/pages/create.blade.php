<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm mới sản phẩm') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <x-form-input name="name" label="Tên sản phẩm" placeholder="VD: Sony Alpha A7 IV..." required />
                    <x-form-input name="price" label="Giá bán (VNĐ)" type="number" placeholder="0" required />
                    <x-form-input name="stock" label="Số lượng tồn kho" type="number" placeholder="0" required />
                    <x-form-textarea name="description" label="Mô tả sản phẩm" rows="4" />

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>{{ __('Lưu sản phẩm') }}</x-primary-button>
                        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Hủy') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
