<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-heading font-semibold tracking-tight text-lg text-ink leading-tight">
                @if (auth()->user()->isAdmin())
                    Bảng điều khiển Quản trị viên (Admin)
                @elseif (auth()->user()->isStaff())
                    Không gian làm việc Nhân viên (Staff)
                @else
                    Tổng quan tài khoản Khách hàng
                @endif
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- 1. GIAO DIỆN RIÊNG CỦA ADMIN --}}
            @if (auth()->user()->isAdmin())
                @include(
                    'modules.Dashboard.components.admin-statistics',
                    ['dashboardData' => $dashboardData]
                )
                <div class="bg-base overflow-hidden shadow-sm sm:rounded-xl p-6 border border-ink/10 border-l-4 border-l-primary">
                    <h3 class="font-heading text-lg font-semibold tracking-tight text-ink mb-2">Khu vực Quản trị Hệ thống</h3>
                    <p class="text-sm text-ink/70 mb-4">Bạn có toàn quyền quản lý sản phẩm, danh mục, đơn hàng và tài khoản người dùng.</p>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <a href="{{ route('admin.products.index') }}" class="p-4 bg-surface hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-heading font-semibold text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-package-light class="w-6 h-6 mb-2 text-ink" />
                            Quản lý Sản phẩm
                        </a>
                        <a href="{{ route('admin.inventory.index') }}" class="p-4 bg-surface hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-heading font-semibold text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-warehouse-light class="w-6 h-6 mb-2 text-primary" />
                            Quản lý Kho hàng
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="p-4 bg-surface hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-heading font-semibold text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-folder-light class="w-6 h-6 mb-2 text-ink" />
                            Quản lý Danh mục
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="p-4 bg-surface hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-heading font-semibold text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-receipt-light class="w-6 h-6 mb-2 text-ink" />
                            Quản lý Đơn hàng
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="p-4 bg-danger hover:brightness-95 rounded-xl border border-danger-ink/20 text-center font-heading font-semibold text-danger-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-users-light class="w-6 h-6 mb-2 text-danger-ink" />
                            Quản lý Người dùng (Chỉ Admin)
                        </a>
                    </div>
                </div>

            {{-- 2. GIAO DIỆN RIÊNG CỦA NHÂN VIÊN (STAFF) --}}
            @elseif (auth()->user()->isStaff())
                <div class="bg-base overflow-hidden shadow-sm sm:rounded-xl p-6 border border-ink/10 border-l-4 border-l-info">
                    <h3 class="font-heading text-lg font-semibold tracking-tight text-ink mb-2">Khu vực Vận hành & Bán hàng</h3>
                    <p class="text-sm text-ink/70 mb-4">Bạn có quyền cập nhật sản phẩm, danh mục và xử lý trạng thái đơn hàng của khách.</p>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.orders.index') }}" class="p-4 bg-info hover:brightness-95 rounded-xl border border-info-ink/20 text-center font-heading font-semibold text-info-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-receipt-light class="w-6 h-6 mb-2 text-info-ink" />
                            Xử lý Đơn hàng mới
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="p-4 bg-surface hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-heading font-semibold text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-package-light class="w-6 h-6 mb-2 text-ink" />
                            Danh sách Sản phẩm
                        </a>
                        <a href="{{ route('admin.inventory.index') }}" class="p-4 bg-surface hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-heading font-semibold text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-warehouse-light class="w-6 h-6 mb-2 text-primary" />
                            Quản lý Tồn kho
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="p-4 bg-surface hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-heading font-semibold text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-folder-light class="w-6 h-6 mb-2 text-ink" />
                            Quản lý Danh mục
                        </a>
                    </div>
                </div>

            {{-- 3. GIAO DIỆN RIÊNG CỦA KHÁCH HÀNG (CUSTOMER) --}}
            @else
                <div class="bg-surface overflow-hidden shadow-sm sm:rounded-xl p-6 border border-ink/10 border-l-4 border-l-success">
                    <h3 class="font-heading text-lg font-semibold tracking-tight text-ink mb-2">Xin chào, {{ auth()->user()->name }}!</h3>
                    <p class="text-sm text-ink/70 mb-4">Chào mừng bạn đến với Camera Store. Dưới đây là các tiện ích mua sắm của bạn:</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('products.index') }}" class="p-4 bg-success hover:brightness-95 rounded-xl border border-success-ink/20 text-center font-medium text-success-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-camera-light class="w-6 h-6 mb-2 text-success-ink" />
                            Khám phá Máy ảnh
                        </a>
                        <a href="{{ route('cart.index') }}" class="p-4 bg-base hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-medium text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-shopping-cart-light class="w-6 h-6 mb-2 text-ink" />
                            Giỏ hàng của tôi
                        </a>
                        <a href="{{ route('orders.index') }}" class="p-4 bg-base hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-medium text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-package-light class="w-6 h-6 mb-2 text-ink" />
                            Lịch sử Đơn hàng
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
