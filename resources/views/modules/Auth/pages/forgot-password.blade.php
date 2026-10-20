<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-heading text-2xl font-semibold tracking-tight text-ink">Khôi phục mật khẩu</h1>
        <p class="text-sm text-ink/70 mt-1">
            Nhập email đăng ký tài khoản của bạn. CameraStore sẽ gửi liên kết an toàn để bạn thiết lập mật khẩu mới.
        </p>
    </div>

    <!-- Trạng thái gửi mail thành công -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4" novalidate>
        @csrf

        <!-- Email Address -->
        <x-form-input
            name="email"
            label="Địa chỉ Email của bạn"
            type="email"
            :value="old('email')"
            placeholder="example@camerastore.vn"
            required
            autofocus
            autocomplete="username"
        />

        <div class="pt-2">
            <x-button variant="primary" size="lg" type="submit" class="w-full">
                Gửi liên kết khôi phục mật khẩu
            </x-button>
        </div>

        <div class="mt-4 text-center text-sm text-ink/70">
            Đã nhớ lại mật khẩu?
            <a href="{{ route('login') }}" class="font-semibold text-ink underline hover:text-ink/80">
                Quay lại Đăng nhập
            </a>
        </div>
    </form>
</x-guest-layout>
