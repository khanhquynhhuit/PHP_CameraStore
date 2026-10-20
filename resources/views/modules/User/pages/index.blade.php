<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-heading font-bold text-xl text-ink leading-tight">
                    {{ __('Quản lý người dùng hệ thống') }}
                </h1>
                <p class="text-xs text-ink/60 mt-0.5">
                    Theo dõi danh sách tài khoản, phân quyền quản trị (Admin/Staff/Customer) và kiểm soát trạng thái hoạt động.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-sand/50 border border-ink/10 text-xs font-semibold text-ink">
                    <span>👥</span>
                    <span>Tổng số: <strong class="text-primary">{{ $users->total() }}</strong> tài khoản</span>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">
            {{-- Bộ lọc & Tìm kiếm người dùng --}}
            @include('modules.User.components.user-filter')

            {{-- Thông tin kết quả tìm kiếm --}}
            <div class="flex items-center justify-between text-xs text-ink/70 px-1">
                <div>
                    Tìm thấy <strong class="text-primary font-bold text-sm">{{ $users->total() }}</strong> tài khoản
                    @if (request('search'))
                        cho từ khóa: <span class="font-semibold text-ink">"{{ request('search') }}"</span>
                    @endif
                    @if (request('role'))
                        - Vai trò: <span class="font-semibold text-ink">{{ request('role') }}</span>
                    @endif
                </div>
                @if ($users->total() > 0)
                    <div>
                        Hiển thị {{ $users->firstItem() }} - {{ $users->lastItem() }}
                    </div>
                @endif
            </div>

            {{-- Bảng danh sách người dùng --}}
            <div class="bg-surface border border-ink/10 rounded-2xl shadow-xs overflow-hidden">
                @include('modules.User.components.user-table')

                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t border-ink/10 bg-sand/20">
                        {{ $users->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        @include('modules.User.services.user-service')
    @endpush
</x-app-layout>
