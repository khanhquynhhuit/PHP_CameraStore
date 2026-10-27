<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-heading font-semibold tracking-tight text-lg text-ink leading-tight">
                @if (auth()->user()->isAdmin())
                    Bảng điều khiển Quản trị viên (Admin)
                @elseif (auth()->user()->isStaff())
                    Không gian làm việc Quản lý (Manager)
                @else
                    Tổng quan tài khoản Khách hàng
                @endif
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- 1. GIAO DIỆN RIÊNG CỦA ADMIN (CHỈ QUẢN LÝ NGƯỜI DÙNG & HỆ THỐNG) --}}
            @if (auth()->user()->isAdmin())
                <div class="bg-base overflow-hidden shadow-sm sm:rounded-xl p-6 border border-ink/10 border-l-4 border-l-primary">
                    <h3 class="font-heading text-lg font-semibold tracking-tight text-ink mb-2">Khu vực Quản trị Hệ thống</h3>
                    <p class="text-sm text-ink/70 mb-4">Bạn phụ trách quản lý tài khoản, trạng thái hoạt động và phân quyền người dùng trong toàn hệ thống CameraStore.vn.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="group p-6 bg-surface hover:bg-sand/30 rounded-xl border border-ink/15 text-center font-heading transition flex flex-col items-center justify-center gap-2.5 shadow-xs hover:border-primary/50 hover:shadow-sm">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                <x-phosphor-users-light class="w-7 h-7 text-primary" />
                            </div>
                            <span class="text-lg font-bold text-ink group-hover:text-primary transition-colors">Quản lý Người dùng & Phân quyền</span>
                            <span class="text-sm text-ink/80 font-sans font-normal">Xem danh sách, phân vai trò Admin/Staff/Customer, khóa & mở khóa tài khoản</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="group p-6 bg-surface hover:bg-sand/30 rounded-xl border border-ink/15 text-center font-heading transition flex flex-col items-center justify-center gap-2.5 shadow-xs hover:border-primary/50 hover:shadow-sm">
                            <div class="w-12 h-12 rounded-full bg-ink/5 flex items-center justify-center text-ink group-hover:scale-110 transition-transform">
                                <x-phosphor-user-gear-light class="w-7 h-7 text-ink" />
                            </div>
                            <span class="text-lg font-bold text-ink group-hover:text-primary transition-colors">Cấu hình Tài khoản Cá nhân</span>
                            <span class="text-sm text-ink/80 font-sans font-normal">Cập nhật hồ sơ admin, đổi mật khẩu và bảo mật đăng nhập</span>
                        </a>
                    </div>
                </div>

            {{-- 2. GIAO DIỆN RIÊNG CỦA NHÂN VIÊN (STAFF: BÁO CÁO DOANH THU & VẬN HÀNH CỬA HÀNG) --}}
            @elseif (auth()->user()->isStaff())
                @include(
                    'modules.Dashboard.components.admin-statistics',
                    ['dashboardData' => $dashboardData]
                )
                <div class="bg-base overflow-hidden shadow-sm sm:rounded-xl p-6 border border-ink/10 border-l-4 border-l-info">
                    <h3 class="font-heading text-lg font-semibold tracking-tight text-ink mb-2">Khu vực Vận hành & Bán hàng</h3>
                    <p class="text-sm text-ink/70 mb-4">Bạn có toàn quyền theo dõi doanh thu tài chính, quản lý sản phẩm, kho hàng, danh mục và điều phối đơn hàng.</p>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.orders.index') }}" class="p-4 bg-info hover:brightness-95 rounded-xl border border-info-ink/20 text-center font-heading font-semibold text-info-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-receipt-light class="w-6 h-6 mb-2 text-info-ink" />
                            Xử lý Đơn hàng
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="p-4 bg-surface hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-heading font-semibold text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-package-light class="w-6 h-6 mb-2 text-ink" />
                            Danh sách Sản phẩm
                        </a>
                        <a href="{{ route('admin.inventory.index') }}" class="p-4 bg-surface hover:bg-primary/20 rounded-xl border border-ink/10 text-center font-heading font-semibold text-ink transition flex flex-col items-center justify-center">
                            <x-phosphor-warehouse-light class="w-6 h-6 mb-2 text-primary" />
                            Quản lý Kho hàng
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
