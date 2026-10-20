<section x-data="{ showAddForm: false }">
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="font-heading text-lg font-semibold text-ink">
                {{ __('Sổ địa chỉ nhận hàng') }}
            </h2>
            <p class="mt-1 text-xs text-ink/70">
                {{ __('Quản lý các địa chỉ giao hàng của bạn để thanh toán đơn hàng nhanh chóng hơn.') }}
            </p>
        </div>

        <button type="button" 
                x-on:click="showAddForm = !showAddForm"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary text-cream hover:bg-primary-dark transition self-start sm:self-auto shadow-xs">
            <span x-text="showAddForm ? '✕ Đóng form' : '+ Thêm địa chỉ mới'"></span>
        </button>
    </header>

    {{-- Form thêm địa chỉ mới (Toggle mở ra khi click nút) --}}
    <div x-show="showAddForm" 
         x-transition 
         class="mt-5 p-5 rounded-2xl bg-sand/30 border border-ink/10 space-y-4">
        <h3 class="font-heading text-sm font-bold text-ink">Thêm địa chỉ giao hàng mới</h3>

        <form method="POST" action="{{ route('profile.addresses.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form-input 
                    name="receiver_name" 
                    label="Họ tên người nhận" 
                    placeholder="Ví dụ: Nguyễn Văn A" 
                    required 
                />

                <x-form-input 
                    name="phone" 
                    label="Số điện thoại nhận hàng" 
                    placeholder="Ví dụ: 0901234567" 
                    required 
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-form-input 
                    name="province" 
                    label="Tỉnh / Thành phố" 
                    placeholder="Hà Nội / TP.HCM..." 
                    required 
                />

                <x-form-input 
                    name="district" 
                    label="Quận / Huyện" 
                    placeholder="Quận 1 / Cầu Giấy..." 
                    required 
                />

                <x-form-input 
                    name="ward" 
                    label="Phường / Xã" 
                    placeholder="Phường Bến Nghé..." 
                    required 
                />
            </div>

            <x-form-input 
                name="detail" 
                label="Địa chỉ chi tiết (Số nhà, tên đường, tòa nhà)" 
                placeholder="Số 123 Đường ABC..." 
                required 
            />

            <div class="flex items-center gap-2">
                <input type="checkbox" 
                       id="is_default" 
                       name="is_default" 
                       value="1" 
                       class="rounded border-ink/20 text-primary shadow-sm focus:ring-primary">
                <label for="is_default" class="text-xs text-ink/80 cursor-pointer">
                    Đặt làm địa chỉ nhận hàng mặc định
                </label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-button variant="primary" size="sm" type="submit">
                    Lưu địa chỉ
                </x-button>
                <button type="button" 
                        x-on:click="showAddForm = false" 
                        class="text-xs font-semibold text-ink/60 hover:text-ink">
                    Hủy bỏ
                </button>
            </div>
        </form>
    </div>

    {{-- Danh sách các địa chỉ hiện có --}}
    <div class="mt-6 space-y-3">
        @forelse ($addresses as $addr)
            <div class="p-4 rounded-xl border transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $addr->is_default ? 'border-primary bg-primary/5' : 'border-ink/10 bg-surface hover:border-ink/20' }}">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm text-ink">{{ $addr->receiver_name }}</span>
                        <span class="text-xs text-ink/60">({{ $addr->phone }})</span>
                        @if ($addr->is_default)
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-primary text-cream">
                                Mặc định
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-ink/70">
                        {{ $addr->detail }}, {{ $addr->ward }}, {{ $addr->district }}, {{ $addr->province }}
                    </p>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0 self-end sm:self-center">
                    @if (! $addr->is_default)
                        <form method="POST" action="{{ route('profile.addresses.default', $addr->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="text-xs font-semibold text-primary hover:text-primary-dark underline">
                                Đặt làm mặc định
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('profile.addresses.destroy', $addr->id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-xs font-semibold text-danger-ink hover:underline ml-2">
                            Xóa
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-6 rounded-xl border border-dashed border-ink/20 text-center text-ink/60 bg-sand/10">
                <span class="text-2xl block mb-1">📍</span>
                <p class="text-xs font-semibold text-ink">Bạn chưa lưu địa chỉ nhận hàng nào</p>
                <p class="text-[11px] text-ink/60 mt-0.5">Bấm nút "Thêm địa chỉ mới" ở trên để lưu địa chỉ giao hàng mặc định.</p>
            </div>
        @endforelse
    </div>
</section>
