<section x-data="{
    showAddForm: {{ $errors->has('province') || $errors->has('ward') || $errors->has('receiver_name') || $errors->has('phone') || $errors->has('detail') ? 'true' : 'false' }},
    deleteModalOpen: false,
    deleteAddressText: '',
    targetDeleteForm: null,

    // Vietnam Provinces 2025 API (OpenAPI v2)
    manualInput: false,
    provinces: [],
    wards: [],
    selectedProvinceCode: '',
    selectedWardCode: '',
    provinceName: '{{ addslashes(old('province', '')) }}',
    wardName: '{{ addslashes(old('ward', '')) }}',
    isLoadingProvinces: false,
    isLoadingWards: false,
    clientError: '',

    async init() {
        await this.loadProvinces();
    },

    async loadProvinces() {
        this.isLoadingProvinces = true;
        try {
            const res = await fetch('https://provinces.open-api.vn/api/v2/p/');
            if (!res.ok) throw new Error('Không thể tải danh sách Tỉnh/Thành');
            this.provinces = await res.json();

            // Khôi phục giá trị cũ nếu reload form có validation error
            if (this.provinceName) {
                const found = this.provinces.find(p => p.name === this.provinceName);
                if (found) {
                    this.selectedProvinceCode = found.code;
                    await this.loadWards(found.code);
                    if (this.wardName) {
                        const foundWard = this.wards.find(w => w.name === this.wardName);
                        if (foundWard) {
                            this.selectedWardCode = foundWard.code;
                        }
                    }
                }
            }
        } catch (e) {
            console.warn('Lỗi gọi API Tỉnh/Thành phố:', e);
        } finally {
            this.isLoadingProvinces = false;
        }
    },

    async onProvinceChange() {
        const found = this.provinces.find(p => String(p.code) === String(this.selectedProvinceCode));
        this.provinceName = found ? found.name : '';
        this.wards = [];
        this.selectedWardCode = '';
        this.wardName = '';
        this.clientError = '';

        if (this.selectedProvinceCode) {
            await this.loadWards(this.selectedProvinceCode);
        }
    },

    async loadWards(provCode) {
        this.isLoadingWards = true;
        try {
            const res = await fetch(`https://provinces.open-api.vn/api/v2/p/${provCode}?depth=2`);
            if (!res.ok) throw new Error('Không thể tải danh sách Phường/Xã');
            const data = await res.json();
            this.wards = data.wards || [];
        } catch (e) {
            console.warn('Lỗi gọi API Phường/Xã:', e);
        } finally {
            this.isLoadingWards = false;
        }
    },

    onWardChange() {
        const found = this.wards.find(w => String(w.code) === String(this.selectedWardCode));
        this.wardName = found ? found.name : '';
        this.clientError = '';
    },

    validateBeforeSubmit(e) {
        if (!this.provinceName.trim()) {
            e.preventDefault();
            this.clientError = 'Vui lòng chọn hoặc nhập Tỉnh / Thành phố.';
            return false;
        }
        if (!this.wardName.trim()) {
            e.preventDefault();
            this.clientError = 'Vui lòng chọn hoặc nhập Phường / Xã.';
            return false;
        }
        this.clientError = '';
        return true;
    },

    openDeleteConfirm(addressText, form) {
        this.deleteAddressText = addressText;
        this.targetDeleteForm = form;
        this.deleteModalOpen = true;
    },

    cancelDelete() {
        this.deleteModalOpen = false;
        this.targetDeleteForm = null;
    },

    confirmDelete() {
        if (this.targetDeleteForm) {
            this.targetDeleteForm.submit();
        }
        this.deleteModalOpen = false;
    }
}">
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

    {{-- Form thêm địa chỉ mới (Cascading Dropdown API Tỉnh - Phường/Xã 2025) --}}
    <div x-show="showAddForm" 
         x-transition 
         class="mt-5 p-5 rounded-2xl bg-sand/30 border border-ink/10 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-heading text-sm font-bold text-ink">Thêm địa chỉ giao hàng mới</h3>
            <button type="button" 
                    x-on:click="manualInput = !manualInput" 
                    class="text-xs text-primary hover:text-primary-dark underline inline-flex items-center gap-1 font-medium">
                <span x-text="manualInput ? '⚡ Chọn từ danh mục Tỉnh/Xã' : '✏️ Nhập tay thủ công'"></span>
            </button>
        </div>

        <form method="POST" 
              action="{{ route('profile.addresses.store') }}" 
              x-on:submit="validateBeforeSubmit($event)"
              class="space-y-4" 
              novalidate>
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

            {{-- Chế độ chọn nhanh Tỉnh / Thành và Phường / Xã qua API v2 --}}
            <div x-show="!manualInput" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Dropdown Tỉnh / Thành phố --}}
                    <div>
                        <label for="select_province" class="block font-medium text-sm text-ink mb-1">
                            Tỉnh / Thành phố <span class="text-danger-ink font-bold">*</span>
                            <span x-show="isLoadingProvinces" class="text-xs text-primary font-normal animate-pulse ml-1">(Đang tải...)</span>
                        </label>
                        <select id="select_province"
                                x-model="selectedProvinceCode"
                                x-on:change="onProvinceChange()"
                                :disabled="isLoadingProvinces"
                                class="border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm block w-full text-sm py-2 px-3 cursor-pointer disabled:opacity-50">
                            <option value="">-- Chọn Tỉnh / Thành phố --</option>
                            <template x-for="p in provinces" :key="p.code">
                                <option :value="p.code" x-text="p.name"></option>
                            </template>
                        </select>
                        <x-input-error for="province" />
                    </div>

                    {{-- Dropdown Phường / Xã --}}
                    <div>
                        <label for="select_ward" class="block font-medium text-sm text-ink mb-1">
                            Phường / Xã <span class="text-danger-ink font-bold">*</span>
                            <span x-show="isLoadingWards" class="text-xs text-primary font-normal animate-pulse ml-1">(Đang tải...)</span>
                        </label>
                        <select id="select_ward"
                                x-model="selectedWardCode"
                                x-on:change="onWardChange()"
                                :disabled="!selectedProvinceCode || isLoadingWards"
                                class="border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm block w-full text-sm py-2 px-3 cursor-pointer disabled:opacity-50 disabled:bg-sand/30">
                            <option value="" x-text="!selectedProvinceCode ? '-- Vui lòng chọn Tỉnh/Thành trước --' : (isLoadingWards ? '-- Đang tải danh sách... --' : '-- Chọn Phường / Xã --')"></option>
                            <template x-for="w in wards" :key="w.code">
                                <option :value="w.code" x-text="w.name"></option>
                            </template>
                        </select>
                        <x-input-error for="ward" />
                    </div>
                </div>
            </div>

            {{-- Chế độ nhập tay (khi người dùng chuyển sang nhập thủ công) --}}
            <div x-show="manualInput" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="manual_province" class="block font-medium text-sm text-ink mb-1">
                            Tỉnh / Thành phố <span class="text-danger-ink font-bold">*</span>
                        </label>
                        <input type="text"
                               id="manual_province"
                               x-model="provinceName"
                               placeholder="Ví dụ: TP. Hồ Chí Minh"
                               class="border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm block w-full text-sm py-2 px-3">
                        <x-input-error for="province" />
                    </div>

                    <div>
                        <label for="manual_ward" class="block font-medium text-sm text-ink mb-1">
                            Phường / Xã <span class="text-danger-ink font-bold">*</span>
                        </label>
                        <input type="text"
                               id="manual_ward"
                               x-model="wardName"
                               placeholder="Ví dụ: Phường Bến Nghé"
                               class="border-ink/20 focus:border-primary focus:ring-primary bg-base text-ink rounded-lg shadow-sm block w-full text-sm py-2 px-3">
                        <x-input-error for="ward" />
                    </div>
                </div>
            </div>

            {{-- Hidden input gửi giá trị Tỉnh và Phường/Xã cho Backend --}}
            <input type="hidden" name="province" :value="provinceName">
            <input type="hidden" name="ward" :value="wardName">

            {{-- Thông báo lỗi phía Client nếu bỏ trống --}}
            <div x-show="clientError" class="p-2.5 rounded-lg bg-danger/10 border border-danger-ink/20 text-xs text-danger-ink font-medium flex items-center gap-2">
                <svg class="w-4 h-4 text-danger-ink flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-text="clientError"></span>
            </div>

            {{-- Địa chỉ chi tiết --}}
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
                        {{ $addr->detail }}, {{ $addr->ward }}{{ $addr->district ? ', ' . $addr->district : '' }}, {{ $addr->province }}
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

                    <form method="POST" action="{{ route('profile.addresses.destroy', $addr->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                x-on:click="openDeleteConfirm('{{ addslashes($addr->detail . ', ' . $addr->ward . ($addr->district ? ', ' . $addr->district : '') . ', ' . $addr->province) }}', $el.closest('form'))"
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

    {{-- Modal Xác nhận Xóa Địa chỉ bằng Alpine.js & Tailwind CSS (Thay thế hoàn toàn confirm trình duyệt) --}}
    <div x-show="deleteModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink/50 backdrop-blur-xs"
         style="display: none;">
        <div x-show="deleteModalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-on:click.outside="cancelDelete()"
             class="bg-surface border border-ink/10 rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
            
            <div class="space-y-2">
                <h3 class="font-heading font-bold text-base text-ink">Xác nhận xóa địa chỉ</h3>
                <p class="text-xs text-ink/75 leading-relaxed">
                    Bạn có chắc chắn muốn xóa địa chỉ nhận hàng:
                    <br>
                    <strong class="text-ink font-semibold mt-1 block" x-text="deleteAddressText"></strong>
                    khỏi sổ địa chỉ của bạn không?
                </p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-ink/10">
                <button type="button" 
                        x-on:click="cancelDelete()"
                        class="px-4 py-2 text-xs font-semibold rounded-xl bg-base border border-ink/15 text-ink hover:bg-ink/5 transition">
                    Hủy bỏ
                </button>
                <button type="button" 
                        x-on:click="confirmDelete()"
                        class="px-4 py-2 text-xs font-semibold rounded-xl bg-danger text-danger-ink hover:bg-danger/80 transition shadow-xs font-heading">
                    Xác nhận xóa
                </button>
            </div>
        </div>
    </div>
</section>
