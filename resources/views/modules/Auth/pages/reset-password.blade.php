<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-heading text-2xl font-semibold tracking-tight text-ink">Đặt lại mật khẩu mới</h1>
        <p class="text-sm text-ink/70 mt-1">
            Thiết lập mật khẩu bảo mật mới cho tài khoản CameraStore của bạn
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <x-form-input
            name="email"
            label="Địa chỉ Email"
            type="email"
            :value="old('email', $request->email)"
            placeholder="example@camerastore.vn"
            required
            autofocus
            autocomplete="username"
        />

        <!-- Password -->
        <x-form-input
            name="password"
            label="Mật khẩu mới"
            type="password"
            placeholder="Tối thiểu 8 ký tự"
            required
            autocomplete="new-password"
        />

        <!-- Confirm Password -->
        <x-form-input
            name="password_confirmation"
            label="Xác nhận mật khẩu mới"
            type="password"
            placeholder="Nhập lại mật khẩu mới"
            required
            autocomplete="new-password"
        />

        <div class="pt-2">
            <x-button variant="primary" size="lg" type="submit" class="w-full">
                Xác nhận đặt lại mật khẩu
            </x-button>
        </div>

        <div class="mt-4 text-center text-sm text-ink/70">
            Quay lại
            <a href="{{ route('login') }}" class="font-semibold text-ink underline hover:text-ink/80">
                Đăng nhập tại đây
            </a>
        </div>
    </form>
</x-guest-layout>
