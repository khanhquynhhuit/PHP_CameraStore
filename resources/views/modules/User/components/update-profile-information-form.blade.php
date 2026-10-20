<section>
    <header>
        <h2 class="font-heading text-lg font-semibold text-ink">
            {{ __('Thông tin cá nhân') }}
        </h2>
        <p class="mt-1 text-xs text-ink/70">
            {{ __('Cập nhật thông tin họ tên, email và số điện thoại liên hệ của bạn.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('patch')

        {{-- Họ và tên --}}
        <div>
            <x-form-input 
                name="name" 
                label="Họ và tên" 
                type="text" 
                :value="old('name', $user->name)" 
                required 
                autofocus 
                placeholder="Nhập họ và tên..."
            />
        </div>

        {{-- Địa chỉ Email --}}
        <div>
            <x-form-input 
                name="email" 
                label="Địa chỉ Email" 
                type="email" 
                :value="old('email', $user->email)" 
                required 
                placeholder="example@gmail.com"
            />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-xs">
                    <p class="text-ink/80">
                        {{ __('Địa chỉ email của bạn chưa được xác thực.') }}

                        <button form="send-verification" class="underline text-primary hover:text-primary-dark font-medium ml-1">
                            {{ __('Bấm vào đây để gửi lại email xác thực.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-600">
                            {{ __('Một liên kết xác thực mới đã được gửi tới hòm thư của bạn.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Số điện thoại --}}
        <div>
            <x-form-input 
                name="phone" 
                label="Số điện thoại" 
                type="text" 
                :value="old('phone', $user->phone)" 
                placeholder="Ví dụ: 0901234567"
            />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-button variant="primary" type="submit">
                {{ __('Lưu thay đổi') }}
            </x-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg inline-flex items-center gap-1"
                >
                    <span>✓</span>
                    <span>{{ __('Đã cập nhật thông tin thành công.') }}</span>
                </p>
            @endif
        </div>
    </form>
</section>
