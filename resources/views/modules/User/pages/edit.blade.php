<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="font-heading font-bold text-xl text-ink leading-tight">
                {{ __('Hồ sơ cá nhân & Cài đặt tài khoản') }}
            </h1>
            <p class="text-xs text-ink/60 mt-0.5">
                @if ($user->isCustomer())
                    {{ __('Quản lý thông tin liên hệ, sổ địa chỉ nhận hàng và các thiết lập bảo mật tài khoản.') }}
                @else
                    {{ __('Quản lý thông tin liên hệ và các thiết lập bảo mật tài khoản.') }}
                @endif
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto space-y-6">
            {{-- 1. Cập nhật thông tin cá nhân (Tên, Email, SĐT) --}}
            <div class="p-6 sm:p-8 bg-surface border border-ink/10 rounded-2xl shadow-xs">
                @include('modules.User.components.update-profile-information-form')
            </div>

            {{-- 2. Sổ địa chỉ nhận hàng (Chỉ hiển thị cho tài khoản Khách hàng) --}}
            @if ($user->isCustomer())
                <div class="p-6 sm:p-8 bg-surface border border-ink/10 rounded-2xl shadow-xs">
                    @include('modules.User.components.user-address-manager')
                </div>
            @endif

            {{-- 3. Đổi mật khẩu tài khoản --}}
            <div class="p-6 sm:p-8 bg-surface border border-ink/10 rounded-2xl shadow-xs">
                @include('modules.User.components.update-password-form')
            </div>

            {{-- 4. Xóa tài khoản --}}
            <div class="p-6 sm:p-8 bg-surface border border-ink/10 rounded-2xl shadow-xs">
                @include('modules.User.components.delete-user-form')
            </div>
        </div>
    </div>

    @push('scripts')
        @include('modules.User.services.user-service')
    @endpush
</x-app-layout>
