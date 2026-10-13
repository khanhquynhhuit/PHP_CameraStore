<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-semibold tracking-tight text-xl text-ink leading-tight">
            {{ __('Đơn hàng của bạn') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface border border-ink/10 overflow-hidden shadow-sm sm:rounded-xl p-6">
                @include('modules.Order.components.order-table')
            </div>
        </div>
    </div>

    @push('scripts')
        @include('modules.Order.services.order-service')
    @endpush
</x-app-layout>
