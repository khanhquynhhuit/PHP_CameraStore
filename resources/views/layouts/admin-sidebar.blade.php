<div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-base">
    <!-- Sidebar dọc bên trái cho Admin & Staff -->
    <aside class="w-64 bg-surface text-ink flex-shrink-0 hidden md:flex flex-col justify-between border-r border-ink/10">
        <div>
            <!-- Logo Quản trị -->
            <div class="h-16 flex items-center px-6 bg-ink border-b border-white/10 text-cream">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="CameraStore.vn" 
                         class="w-8 h-8 object-contain">
                    <div class="flex flex-col">
                        <span class="font-heading text-base font-bold tracking-tight text-cream leading-none">
                            CAMERASTORE<span class="text-accent">.VN</span>
                        </span>
                        <span class="text-[10px] uppercase tracking-widest text-cream/70 font-semibold mt-0.5">
                            @if (Auth::user()->isAdmin())
                                Admin Portal
                            @else
                                Staff Workspace
                            @endif
                        </span>
                    </div>
                </a>
            </div>

            <!-- Danh sách Menu Quản trị -->
            <nav class="p-4 space-y-1.5 text-sm font-medium">
                <p class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-ink/60">
                    Tổng quan
                </p>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-primary text-cream font-semibold shadow-sm' : 'text-ink/80 hover:bg-sand/60 hover:text-primary-dark' }}">
                    <span>📊</span>
                    <span>Bảng điều khiển</span>
                </a>

                <p class="px-3 pt-4 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-ink/60">
                    Quản lý Cửa hàng
                </p>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.products.*') ? 'bg-primary text-cream font-semibold shadow-sm' : 'text-ink/80 hover:bg-sand/60 hover:text-primary-dark' }}">
                    <span>📦</span>
                    <span>Quản lý Sản phẩm</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'bg-primary text-cream font-semibold shadow-sm' : 'text-ink/80 hover:bg-sand/60 hover:text-primary-dark' }}">
                    <span>🗂️</span>
                    <span>Quản lý Danh mục</span>
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.orders.*') ? 'bg-primary text-cream font-semibold shadow-sm' : 'text-ink/80 hover:bg-sand/60 hover:text-primary-dark' }}">
                    <span>🧾</span>
                    <span>Quản lý Đơn hàng</span>
                </a>

                @if (Auth::user()->isAdmin())
                    <p class="px-3 pt-4 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-ink/60">
                        Hệ thống (Chỉ Admin)
                    </p>

                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-primary text-cream font-semibold shadow-sm' : 'text-ink/80 hover:bg-sand/60 hover:text-primary-dark' }}">
                        <span>👥</span>
                        <span>Quản lý Người dùng</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Nút chuyển ra xem giao diện Cửa hàng của Khách -->
        <div class="p-4 border-t border-ink/10 bg-base/60 space-y-2">
            <x-button variant="outline" size="sm" :href="route('home')" class="w-full">
                <span>🌐</span>
                <span>Xem giao diện Khách hàng</span>
            </x-button>
        </div>
    </aside>

    <!-- Khu vực Nội dung bên phải -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar Quản trị -->
        <header class="h-16 bg-surface border-b border-ink/10 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-4">
                @isset($header)
                    <div>{{ $header }}</div>
                @else
                    <h1 class="font-heading text-lg font-semibold tracking-tight text-ink">Hệ thống Quản trị CameraStore.vn</h1>
                @endisset
            </div>

            <!-- Thông tin tài khoản Admin / Staff -->
            <div class="flex items-center gap-4">
                <x-button variant="secondary" size="sm" :href="route('home')" class="hidden sm:inline-flex">
                    🌐 Cửa hàng
                </x-button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2.5 px-3 py-1.5 border border-ink/10 text-xs font-semibold rounded-lg text-ink bg-base hover:bg-primary/20 focus:outline-none transition">
                            <span class="font-bold text-ink">{{ Auth::user()->name }}</span>

                            @if (Auth::user()->isAdmin())
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-danger text-danger-ink">
                                    {{ Auth::user()->role->label() }}
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-info text-info-ink">
                                    {{ Auth::user()->role->label() }}
                                </span>
                            @endif

                            <svg class="fill-current h-4 w-4 text-ink/70" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('dashboard')">
                            Bảng điều khiển
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            Hồ sơ cá nhân
                        </x-dropdown-link>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                Đăng xuất
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        <!-- Nội dung chính của trang Admin / Staff -->
        <main class="flex-1 p-4 sm:p-8">
            <div class="max-w-7xl mx-auto mb-4">
                <x-alert />
            </div>
            {{ $slot }}
        </main>
    </div>
</div>
