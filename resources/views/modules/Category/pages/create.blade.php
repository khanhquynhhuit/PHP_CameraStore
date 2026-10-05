<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm danh mục mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <x-form-input name="name" label="Tên danh mục" placeholder="VD: Máy ảnh Mirrorless, Ống kính..." required />
                    <x-form-input name="slug" label="Đường dẫn tĩnh (Slug)" placeholder="VD: may-anh-mirrorless" />
                    <x-form-textarea name="description" label="Mô tả danh mục" rows="3" />

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>{{ __('Lưu danh mục') }}</x-primary-button>
                        <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Hủy') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
