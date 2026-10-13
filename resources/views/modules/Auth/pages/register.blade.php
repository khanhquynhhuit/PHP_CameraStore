<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-heading text-2xl font-semibold tracking-tight text-ink">Đăng ký tài khoản</h1>
        <p class="text-sm text-ink/70 mt-1">Tạo tài khoản khách hàng mới tại Camera Store</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <x-form-input
            name="name"
            label="Họ và tên"
            type="text"
            placeholder="Nguyễn Văn A"
            required
            autofocus
            autocomplete="name"
        />

        <!-- Email Address -->
        <x-form-input
            name="email"
            label="Địa chỉ Email"
            type="email"
            placeholder="example@gmail.com"
            required
            autocomplete="username"
        />

        <!-- Password -->
        <x-form-input
            name="password"
            label="Mật khẩu"
            type="password"
            placeholder="Tối thiểu 8 ký tự"
            required
            autocomplete="new-password"
        />

        <!-- Confirm Password -->
        <x-form-input
            name="password_confirmation"
            label="Xác nhận mật khẩu"
            type="password"
            placeholder="Nhập lại mật khẩu"
            required
            autocomplete="new-password"
        />

        <div class="mt-6">
            <x-button variant="primary" size="lg" type="submit" class="w-full">
                Đăng ký tài khoản
            </x-button>
        </div>

        <div class="mt-4 text-center text-sm text-ink/70">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="font-semibold text-ink underline hover:text-ink/80">
                Đăng nhập tại đây
            </a>
        </div>
    </form>
</x-guest-layout>
