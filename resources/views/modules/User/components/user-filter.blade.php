@php
    $hasActiveFilter = request()->filled('search') || request()->filled('role') || request()->filled('is_active');
@endphp

<div class="bg-surface border border-ink/10 p-5 rounded-2xl shadow-xs space-y-4">
    <form method="GET" action="{{ route('admin.users.index') }}" id="userFilterForm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            {{-- Tìm theo Tên, Email hoặc Số điện thoại --}}
            <div class="lg:col-span-5">
                <label for="search" class="block text-xs font-semibold text-ink/70 mb-1">
                    Tìm kiếm người dùng
                </label>
                <div class="relative">
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nhập họ tên, email hoặc số điện thoại..." 
                           class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                </div>
            </div>

            {{-- Lọc theo Vai trò (Role) --}}
            <div class="lg:col-span-3">
                <label for="role" class="block text-xs font-semibold text-ink/70 mb-1">
                    Vai trò người dùng
                </label>
                <select id="role" 
                        name="role" 
                        class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                    <option value="">-- Tất cả vai trò --</option>
                    @foreach ($roles as $roleOption)
                        <option value="{{ $roleOption->value }}" {{ request('role') === $roleOption->value ? 'selected' : '' }}>
                            {{ $roleOption->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Lọc theo Trạng thái kích hoạt --}}
            <div class="lg:col-span-2">
                <label for="is_active" class="block text-xs font-semibold text-ink/70 mb-1">
                    Trạng thái
                </label>
                <select id="is_active" 
                        name="is_active" 
                        class="w-full text-sm rounded-xl border-ink/15 bg-base text-ink focus:border-primary focus:ring-primary py-2 px-3">
                    <option value="">-- Tất cả --</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Đã bị khóa</option>
                </select>
            </div>

            {{-- Nút bấm tìm kiếm & đặt lại --}}
            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" 
                        class="flex-1 py-2 px-3 bg-primary text-cream rounded-xl text-sm font-semibold hover:bg-primary-dark transition text-center shadow-xs">
                    Lọc dữ liệu
                </button>
                @if ($hasActiveFilter)
                    <a href="{{ route('admin.users.index') }}" 
                       title="Xóa bộ lọc" 
                       class="py-2 px-3 bg-base border border-ink/15 text-ink/70 rounded-xl text-sm font-semibold hover:bg-ink/5 transition text-center">
                        Đặt lại
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>
