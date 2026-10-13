<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if (auth()->user()->isAdmin())
                    Bảng điều khiển Quản trị viên (Admin)
                @elseif (auth()->user()->isStaff())
                    Không gian làm việc Nhân viên (Staff)
                @else
                    Tổng quan tài khoản Khách hàng
                @endif
            </h2>
            <!-- <span class="text-sm text-gray-500">
                Vai trò hiện tại: <strong>{{ auth()->user()->role->label() }}</strong>
            </span> -->
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. GIAO DIỆN RIÊNG CỦA ADMIN --}}
            @if (auth()->user()->isAdmin())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Khu vực Quản trị Hệ thống</h3>
                    <p class="text-sm text-gray-600 mb-4">Bạn có toàn quyền quản lý sản phẩm, danh mục, đơn hàng và tài khoản người dùng.</p>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.products.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 rounded-lg border text-center font-medium text-indigo-600">
                            📦 Quản lý Sản phẩm
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 rounded-lg border text-center font-medium text-indigo-600">
                            🗂️ Quản lý Danh mục
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 rounded-lg border text-center font-medium text-indigo-600">
                            🧾 Quản lý Đơn hàng
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="p-4 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 text-center font-medium text-red-700">
                            👥 Quản lý Người dùng (Chỉ Admin)
                        </a>
                    </div>
                </div>

            {{-- 2. GIAO DIỆN RIÊNG CỦA NHÂN VIÊN (STAFF) --}}
            @elseif (auth()->user()->isStaff())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Khu vực Vận hành & Bán hàng</h3>
                    <p class="text-sm text-gray-600 mb-4">Bạn có quyền cập nhật sản phẩm, danh mục và xử lý trạng thái đơn hàng của khách.</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.orders.index') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 text-center font-medium text-blue-700">
                            🧾 Xử lý Đơn hàng mới
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 rounded-lg border text-center font-medium text-indigo-600">
                            📦 Cập nhật Sản phẩm & Kho
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 rounded-lg border text-center font-medium text-indigo-600">
                            🗂️ Quản lý Danh mục
                        </a>
                    </div>
                </div>

            {{-- 3. GIAO DIỆN RIÊNG CỦA KHÁCH HÀNG (CUSTOMER) --}}
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Xin chào, {{ auth()->user()->name }}!</h3>
                    <p class="text-sm text-gray-600 mb-4">Chào mừng bạn đến với Camera Store. Dưới đây là các tiện ích mua sắm của bạn:</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('products.index') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 text-center font-medium text-green-700">
                            📷 Khám phá Máy ảnh
                        </a>
                        <a href="{{ route('cart.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 rounded-lg border text-center font-medium text-indigo-600">
                            🛒 Giỏ hàng của tôi
                        </a>
                        <a href="{{ route('orders.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 rounded-lg border text-center font-medium text-indigo-600">
                            📦 Lịch sử Đơn hàng
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
