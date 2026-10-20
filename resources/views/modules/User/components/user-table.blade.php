<div x-data="{
    modalOpen: false,
    modalTitle: '',
    modalMessage: '',
    modalType: 'danger',
    targetForm: null,
    previousSelectValue: '',
    targetSelect: null,

    openConfirm(title, message, type, form) {
        this.modalTitle = title;
        this.modalMessage = message;
        this.modalType = type;
        this.targetForm = form;
        this.targetSelect = null;
        this.modalOpen = true;
    },

    openRoleConfirm(userName, newRoleLabel, form, selectEl, oldRole) {
        this.modalTitle = 'Thay đổi vai trò người dùng';
        this.modalMessage = 'Bạn có chắc chắn muốn thay đổi vai trò của <strong>' + userName + '</strong> sang <strong>' + newRoleLabel + '</strong>?';
        this.modalType = 'primary';
        this.targetForm = form;
        this.targetSelect = selectEl;
        this.previousSelectValue = oldRole;
        this.modalOpen = true;
    },

    cancelModal() {
        if (this.targetSelect && this.previousSelectValue) {
            this.targetSelect.value = this.previousSelectValue;
        }
        this.modalOpen = false;
        this.targetForm = null;
        this.targetSelect = null;
    },

    submitAction() {
        if (this.targetForm) {
            this.targetForm.submit();
        }
        this.modalOpen = false;
    }
}" class="overflow-x-auto relative">
    <table class="min-w-full divide-y divide-ink/10 text-sm">
        <thead class="bg-sand/40">
            <tr>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">ID</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">Người dùng</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">Email</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">Số điện thoại</th>
                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase">Vai trò (Role)</th>
                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold text-ink/70 uppercase">Trạng thái</th>
                <th scope="col" class="px-5 py-3.5 text-right text-xs font-semibold text-ink/70 uppercase">Hành động</th>
            </tr>
        </thead>
        <tbody class="bg-surface divide-y divide-ink/10">
            @forelse ($users as $u)
                @php
                    $isCurrentUser = auth()->id() === $u->id;
                    $roleValue = $u->role instanceof \App\Models\Enums\RoleName ? $u->role->value : (string) $u->role;
                    $roleLabel = $u->role instanceof \App\Models\Enums\RoleName ? $u->role->label() : ucfirst($roleValue);
                @endphp
                <tr class="hover:bg-sand/15 transition-colors">
                    {{-- Cột ID --}}
                    <td class="px-5 py-4 whitespace-nowrap text-xs font-mono text-ink/60">
                        #{{ $u->id }}
                    </td>

                    {{-- Cột Họ tên & Avatar --}}
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center font-heading font-bold text-primary text-sm flex-shrink-0">
                                {{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-ink flex items-center gap-1.5">
                                    <span>{{ $u->name }}</span>
                                    @if ($isCurrentUser)
                                        <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded bg-primary/15 text-primary">Bạn</span>
                                    @endif
                                </div>
                                <div class="text-xs text-ink/50 mt-0.5">
                                    Tham gia: {{ $u->created_at ? $u->created_at->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Cột Email --}}
                    <td class="px-5 py-4 whitespace-nowrap text-xs font-medium text-ink/80">
                        {{ $u->email }}
                    </td>

                    {{-- Cột Số điện thoại --}}
                    <td class="px-5 py-4 whitespace-nowrap text-xs text-ink/80 font-mono">
                        {{ $u->phone ?: 'Chưa cập nhật' }}
                    </td>

                    {{-- Cột Role Badge & Form cập nhật vai trò qua Modal --}}
                    <td class="px-5 py-4 whitespace-nowrap text-xs">
                        <div class="flex items-center gap-2">
                            {{-- Badge vai trò hiện tại --}}
                            @if ($u->isAdmin())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-danger/20 text-danger-ink border border-danger/30">
                                    🛡️ {{ $roleLabel }}
                                </span>
                            @elseif ($u->isStaff())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-info/20 text-info-ink border border-info/30">
                                    💼 {{ $roleLabel }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-success/20 text-success-ink border border-success/30">
                                    👤 {{ $roleLabel }}
                                </span>
                            @endif

                            {{-- Form đổi quyền nhanh qua Modal xác nhận --}}
                            @if (! $isCurrentUser)
                                <form method="POST" action="{{ route('admin.users.update-role', $u->id) }}" class="inline-block ml-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" 
                                            x-on:change="openRoleConfirm('{{ addslashes($u->name) }}', $el.options[$el.selectedIndex].text, $el.closest('form'), $el, '{{ $roleValue }}')" 
                                            class="text-[11px] py-1 px-2 border border-ink/15 rounded-lg bg-base text-ink focus:border-primary focus:ring-primary cursor-pointer">
                                        @foreach ($roles as $r)
                                            <option value="{{ $r->value }}" {{ $roleValue === $r->value ? 'selected' : '' }}>
                                                {{ $r->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </div>
                    </td>

                    {{-- Cột Trạng thái (Hoạt động / Đã khóa) --}}
                    <td class="px-5 py-4 whitespace-nowrap text-center">
                        @if ($u->is_active)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-success/25 text-success-ink border border-success/40">
                                <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                                Hoạt động
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-danger/25 text-danger-ink border border-danger/40">
                                <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                Đã bị khóa
                            </span>
                        @endif
                    </td>

                    {{-- Cột Hành động (Nút Khóa / Mở khóa qua Modal) --}}
                    <td class="px-5 py-4 whitespace-nowrap text-right text-xs">
                        @if ($isCurrentUser)
                            <span class="text-ink/40 text-xs italic px-3 py-1.5">
                                Tài khoản hiện tại
                            </span>
                        @else
                            <form method="POST" action="{{ route('admin.users.toggle-status', $u->id) }}" class="inline-block">
                                @csrf
                                @method('PATCH')
                                @if ($u->is_active)
                                    <button type="button" 
                                            x-on:click="openConfirm('Khóa tài khoản người dùng', 'Bạn có chắc chắn muốn KHÓA tài khoản của <strong>{{ addslashes($u->name) }}</strong>? Khi bị khóa, người dùng sẽ không thể đăng nhập vào hệ thống.', 'danger', $el.closest('form'))"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-danger/30 text-danger-ink bg-danger/10 hover:bg-danger/25 transition font-semibold">
                                        <span>🔒</span>
                                        <span>Khóa tài khoản</span>
                                    </button>
                                @else
                                    <button type="button" 
                                            x-on:click="openConfirm('Mở khóa tài khoản', 'Bạn có chắc chắn muốn MỞ KHÓA tài khoản của <strong>{{ addslashes($u->name) }}</strong>? Người dùng sẽ có thể đăng nhập lại bình thường.', 'primary', $el.closest('form'))"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-success/40 text-success-ink bg-success/15 hover:bg-success/30 transition font-semibold">
                                        <span>🔓</span>
                                        <span>Mở khóa</span>
                                    </button>
                                @endif
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-ink/60">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <span class="text-4xl">👥</span>
                            <p class="font-semibold text-base text-ink">Không tìm thấy người dùng nào</p>
                            <p class="text-xs text-ink/60 max-w-sm">
                                Thử thay đổi từ khóa tìm kiếm hoặc bỏ các tiêu chí lọc để xem thêm kết quả.
                            </p>
                            @if (request()->hasAny(['search', 'role', 'is_active']))
                                <a href="{{ route('admin.users.index') }}" 
                                   class="text-xs font-semibold text-primary underline hover:text-primary-dark">
                                    Xóa bộ lọc
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Modal Hộp thoại xác nhận thao tác Javascript/Alpine.js chuẩn đẹp --}}
    <div x-show="modalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink/50 backdrop-blur-xs"
         style="display: none;">
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-on:click.outside="cancelModal()"
             class="bg-surface border border-ink/10 rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
            
            <div class="flex items-start gap-3.5">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                     :class="modalType === 'danger' ? 'bg-danger/25 text-danger-ink border border-danger/30' : 'bg-primary/20 text-primary-dark border border-primary/30'">
                    <span x-text="modalType === 'danger' ? '🔒' : '🛡️'"></span>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-heading font-bold text-base text-ink" x-text="modalTitle"></h3>
                    <p class="text-xs text-ink/75 mt-1.5 leading-relaxed" x-html="modalMessage"></p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-ink/10">
                <button type="button" 
                        x-on:click="cancelModal()"
                        class="px-4 py-2 text-xs font-semibold rounded-xl bg-base border border-ink/15 text-ink hover:bg-ink/5 transition">
                    Hủy bỏ
                </button>
                <button type="button" 
                        x-on:click="submitAction()"
                        :class="modalType === 'danger' ? 'bg-danger text-danger-ink hover:bg-danger/80' : 'bg-primary text-cream hover:bg-primary-dark'"
                        class="px-4 py-2 text-xs font-semibold rounded-xl transition shadow-xs font-heading">
                    Xác nhận thực hiện
                </button>
            </div>
        </div>
    </div>
</div>
