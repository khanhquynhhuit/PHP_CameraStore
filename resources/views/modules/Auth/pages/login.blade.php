<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-heading text-2xl font-semibold tracking-tight text-ink">Đăng nhập Camera Store</h1>
        <p class="text-sm text-ink/70 mt-1">Nhập thông tin tài khoản của bạn để tiếp tục</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <x-form-input
            name="email"
            label="Địa chỉ Email"
            type="email"
            placeholder="example@camera.test"
            required
            autofocus
            autocomplete="username"
        />

        <!-- Password -->
        <x-form-input
            name="password"
            label="Mật khẩu"
            type="password"
            placeholder="••••••••"
            required
            autocomplete="current-password"
        />

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-ink/20 text-primary shadow-sm focus:ring-primary" name="remember">
                <span class="ms-2 text-sm text-ink/70">Ghi nhớ đăng nhập</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-ink hover:text-ink/80" href="{{ route('password.request') }}">
                    Quên mật khẩu?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-button variant="primary" size="lg" type="submit" class="w-full">
                Đăng nhập
            </x-button>
        </div>

        <div class="mt-4 text-center text-sm text-ink/70">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="font-semibold text-ink underline hover:text-ink/80">
                Đăng ký ngay
            </a>
        </div>
    </form>
</x-guest-layout>
