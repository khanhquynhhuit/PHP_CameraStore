<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-heading font-semibold tracking-tight text-xl text-ink leading-tight">
                {{ __('Quản lý danh mục') }}
            </h2>
            <x-button variant="primary" size="sm" :href="route('admin.categories.create')">
                + Thêm danh mục
            </x-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface border border-ink/10 overflow-hidden shadow-sm sm:rounded-xl p-6">
                @include('modules.Category.components.category-table')
            </div>
        </div>
    </div>

    @push('scripts')
        @include('modules.Category.services.category-service')
    @endpush
</x-app-layout>
