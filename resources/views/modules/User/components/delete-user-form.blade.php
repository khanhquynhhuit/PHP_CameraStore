<section class="space-y-4">
    <header>
        <h2 class="font-heading text-lg font-semibold text-danger-ink">
            {{ __('Xóa tài khoản người dùng') }}
        </h2>

        <p class="mt-1 text-xs text-ink/70">
            {{ __('Khi tài khoản bị xóa, toàn bộ dữ liệu đơn hàng và thông tin cá nhân của bạn sẽ bị hủy vĩnh viễn. Vui lòng cân nhắc kỹ trước khi thực hiện thao tác này.') }}
        </p>
    </header>

    <x-button
        variant="danger"
        size="sm"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        {{ __('Xóa tài khoản vĩnh viễn') }}
    </x-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="font-heading text-base font-bold text-ink">
                {{ __('Bạn có chắc chắn muốn xóa tài khoản của mình?') }}
            </h2>

            <p class="mt-2 text-xs text-ink/70">
                {{ __('Hành động này không thể hoàn tác. Toàn bộ thông tin tài khoản và dữ liệu liên quan sẽ bị xóa vĩnh viễn. Vui lòng nhập mật khẩu hiện tại của bạn để xác nhận.') }}
            </p>

            <div class="mt-4">
                <x-form-input
                    id="password"
                    name="password"
                    type="password"
                    label="Mật khẩu xác nhận"
                    placeholder="Nhập mật khẩu của bạn..."
                    required
                />
                @if ($errors->userDeletion->has('password'))
                    <p class="text-xs text-danger-ink font-semibold mt-1">
                        {{ $errors->userDeletion->first('password') }}
                    </p>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-button variant="secondary" size="sm" type="button" x-on:click="$dispatch('close')">
                    {{ __('Hủy bỏ') }}
                </x-button>

                <x-button variant="danger" size="sm" type="submit">
                    {{ __('Xác nhận xóa tài khoản') }}
                </x-button>
            </div>
        </form>
    </x-modal>
</section>
