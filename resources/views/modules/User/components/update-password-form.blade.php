<section>
    <header>
        <h2 class="font-heading text-lg font-semibold text-ink">
            {{ __('Đổi mật khẩu tài khoản') }}
        </h2>

        <p class="mt-1 text-xs text-ink/70">
            {{ __('Đảm bảo tài khoản của bạn sử dụng mật khẩu dài và ngẫu nhiên để tăng tính bảo mật.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('put')

        <div>
            <x-form-input 
                id="update_password_current_password" 
                name="current_password" 
                label="Mật khẩu hiện tại" 
                type="password" 
                placeholder="••••••••" 
                required 
                autocomplete="current-password" 
            />
            @if ($errors->updatePassword->has('current_password'))
                <p class="text-xs text-danger-ink font-semibold mt-1">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        <div>
            <x-form-input 
                id="update_password_password" 
                name="password" 
                label="Mật khẩu mới" 
                type="password" 
                placeholder="Tối thiểu 8 ký tự..." 
                required 
                autocomplete="new-password" 
            />
            @if ($errors->updatePassword->has('password'))
                <p class="text-xs text-danger-ink font-semibold mt-1">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>

        <div>
            <x-form-input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                label="Xác nhận mật khẩu mới" 
                type="password" 
                placeholder="Nhập lại mật khẩu mới..." 
                required 
                autocomplete="new-password" 
            />
            @if ($errors->updatePassword->has('password_confirmation'))
                <p class="text-xs text-danger-ink font-semibold mt-1">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </p>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-button variant="primary" type="submit">
                {{ __('Cập nhật mật khẩu') }}
            </x-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg inline-flex items-center gap-1"
                >
                    <span>✓</span>
                    <span>{{ __('Đã thay đổi mật khẩu thành công.') }}</span>
                </p>
            @endif
        </div>
    </form>
</section>
