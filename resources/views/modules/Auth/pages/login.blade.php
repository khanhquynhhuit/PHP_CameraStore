<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900">Đăng nhập Camera Store</h1>
        <p class="text-sm text-gray-500 mt-1">Nhập thông tin tài khoản của bạn để tiếp tục</p>
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
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ghi nhớ đăng nhập</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-indigo-600 hover:text-indigo-800" href="{{ route('password.request') }}">
                    Quên mật khẩu?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-2.5">
                Đăng nhập
            </x-primary-button>
        </div>

        <div class="mt-4 text-center text-sm text-gray-600">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800">
                Đăng ký ngay
            </a>
        </div>
    </form>
</x-guest-layout>
