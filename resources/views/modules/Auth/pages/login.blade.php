<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-heading text-2xl font-semibold tracking-tight text-ink">Đăng nhập Camera Store</h1>
        <p class="text-sm text-ink/70 mt-1">Nhập thông tin tài khoản của bạn để tiếp tục</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @php
        $emailError = $errors->first('email');
        $isRateLimited = false;
        $remainingSeconds = 0;
        if ($emailError && preg_match('/sau\s+(\d+)\s+giây/i', $emailError, $matches)) {
            $isRateLimited = true;
            $remainingSeconds = (int) $matches[1];
        }
    @endphp

    @if ($isRateLimited)
        {{-- Hộp thông báo Chống spam (Rate Limit) có đồng hồ đếm ngược Realtime bằng Alpine.js --}}
        <div x-data="{
                seconds: {{ $remainingSeconds }},
                timer: null,
                init() {
                    this.timer = setInterval(() => {
                        if (this.seconds > 0) {
                            this.seconds--;
                        } else {
                            clearInterval(this.timer);
                        }
                    }, 1000);
                }
             }"
             class="mb-5 p-4 rounded-xl bg-danger/20 border border-danger/40 text-danger-ink shadow-xs">
            <div class="flex items-start gap-2.5">
                <span class="text-2xl leading-none">⏳</span>
                <div class="flex-1">
                    <p class="font-heading font-bold text-xs uppercase tracking-wider text-danger-ink">
                        Tài khoản tạm thời bị giới hạn đăng nhập
                    </p>
                    <template x-if="seconds > 0">
                        <p class="text-xs mt-1 leading-relaxed">
                            Bạn đã thử đăng nhập sai quá nhiều lần. Vui lòng đợi
                            <strong class="font-mono text-sm font-bold underline px-1 text-danger-ink" x-text="seconds + 's'"></strong>
                            để thử lại.
                        </p>
                    </template>
                    <template x-if="seconds <= 0">
                        <p class="text-xs mt-1 font-bold text-emerald-700 flex items-center gap-1">
                            <span>✓</span>
                            <span>Thời gian chờ đã hết! Bạn có thể bấm Đăng nhập lại ngay bây giờ.</span>
                        </p>
                    </template>
                </div>
            </div>
        </div>
        @php
            // Ẩn lỗi tĩnh để không bị hiển thị trùng chữ '40 giây' đóng băng bên dưới ô input
            $errors->getBag('default')->forget('email');
        @endphp
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <!-- Email Address -->
        <x-form-input
            name="email"
            label="Địa chỉ Email"
            type="email"
            placeholder="admin@camerastore.vn"
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
