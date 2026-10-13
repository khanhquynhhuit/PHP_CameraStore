<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm font-sans">
    <!-- Top Bar (Hotline & Khuyến mãi giống Kyma.vn) -->
    <div class="bg-gray-900 text-gray-300 text-xs py-1.5 px-4 hidden sm:block">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-4">
                <span>🔥 <strong class="text-white">Hotline tư vấn máy ảnh:</strong> <a href="tel:19006789" class="text-red-400 font-bold hover:underline">1900 6789</a></span>
                <span class="text-gray-500">|</span>
                <span>Hệ thống Showroom: Hà Nội - Đà Nẵng - TP. Hồ Chí Minh</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-amber-400 font-semibold">Trả góp 0% lãi suất</span>
                <span class="text-gray-500">|</span>
                <span>Bảo hành chính hãng toàn quốc</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20 gap-4 sm:gap-8">
            <!-- Brand Logo (Đậm chất Kyma với tone Đỏ + Đen) -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center font-black text-xl shadow-md shadow-red-200">
                        📷
                    </span>
                    <div class="flex flex-col">
                        <span class="text-2xl font-black tracking-tight text-gray-900 leading-none">
                            KYMA<span class="text-red-600">.VN</span>
                        </span>
                        <span class="text-[9px] uppercase tracking-widest text-gray-400 font-bold">Máy Ảnh & Thiết Bị Số</span>
                    </div>
                </a>
            </div>

            <!-- Thanh tìm kiếm trung tâm lớn giống Kyma.vn -->
            <div class="flex-1 max-w-xl hidden md:block">
                <form action="{{ route('products.index') }}" method="GET" class="relative">
                    <input type="text" 
                           name="keyword" 
                           placeholder="Bạn cần tìm máy ảnh, ống kính Sony, Canon, Fujifilm..." 
                           class="w-full pl-4 pr-24 py-2.5 rounded-full border-2 border-red-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-600">
                    <button type="submit" 
                            class="absolute right-1 top-1 bottom-1 px-5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-full transition flex items-center gap-1 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Tìm kiếm</span>
                    </button>
                </form>
            </div>

            <!-- Right Actions (Showroom, Giỏ hàng & User) -->
            <div class="hidden sm:flex items-center gap-4">
                <!-- Mini Cart Dropdown -->
                <x-mini-cart />

                @auth
                    <!-- Settings Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-xs font-semibold rounded-lg text-gray-700 bg-white hover:text-red-600 hover:border-red-300 focus:outline-none transition">
                                <div>
                                    <span class="text-gray-400 block text-[10px]">Xin chào,</span>
                                    <span class="font-bold text-gray-900">{{ Auth::user()->name }}</span>
                                </div>
                                <div class="ms-1.5">
                                    <svg class="fill-current h-4 w-4 text-gray-400" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Hồ sơ cá nhân') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('orders.index')">
                                {{ __('Đơn hàng của tôi') }}
                            </x-dropdown-link>

                            @if (in_array(Auth::user()->role?->value ?? Auth::user()->role, ['admin', 'staff']))
                                <x-dropdown-link :href="route('admin.orders.index')" class="text-red-600 font-bold">
                                    {{ __('Quản trị hệ thống') }}
                                </x-dropdown-link>
                            @endif

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Đăng xuất') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="text-xs font-bold text-gray-700 hover:text-red-600 px-3 py-2 border border-gray-200 rounded-lg hover:border-red-300 transition">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="text-xs font-bold text-white bg-red-600 hover:bg-red-700 px-3.5 py-2 rounded-lg transition shadow-sm shadow-red-200">
                            Đăng ký
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="flex items-center sm:hidden gap-2">
                <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-700">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </a>

                <button @click="open = ! open" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Sub Mega Menu Bar (Giống Kyma.vn) -->
        <div class="hidden sm:flex items-center gap-6 py-2.5 border-t border-gray-100 text-sm font-semibold text-gray-700">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-red-600' : 'hover:text-red-600' }} flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                Trang chủ
            </a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'text-red-600' : 'hover:text-red-600' }} transition">
                Máy Ảnh Chuyên Nghiệp
            </a>
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'text-red-600' : 'hover:text-red-600' }} transition">
                Danh Mục Thiết Bị
            </a>
            <a href="{{ route('products.index', ['brand' => 'Sony']) }}" class="hover:text-red-600 transition">
                Sony Alpha
            </a>
            <a href="{{ route('products.index', ['brand' => 'Canon']) }}" class="hover:text-red-600 transition">
                Canon EOS
            </a>
            <a href="{{ route('products.index', ['brand' => 'Fujifilm']) }}" class="hover:text-red-600 transition">
                Fujifilm X
            </a>
            <span class="ms-auto text-xs text-red-600 font-bold bg-red-50 px-2.5 py-1 rounded-full animate-pulse">
                ⚡ Flash Sale Giảm Đến 30%
            </span>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200 bg-white px-4 py-3">
        <!-- Search bar mobile -->
        <form action="{{ route('products.index') }}" method="GET" class="relative mb-3">
            <input type="text" name="keyword" placeholder="Tìm kiếm máy ảnh..." class="w-full px-3 py-2 text-sm border rounded-lg pr-10">
            <button type="submit" class="absolute right-2 top-2 text-red-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </form>

        <div class="space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Trang chủ</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">Tất cả sản phẩm</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">Danh mục thiết bị</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">Giỏ hàng</x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('orders.index')">Đơn hàng của tôi</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">Hồ sơ cá nhân</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        Đăng xuất
                    </x-responsive-nav-link>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="{{ route('login') }}" class="text-center py-2 border rounded-lg text-sm font-bold text-gray-700">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="text-center py-2 bg-red-600 text-white rounded-lg text-sm font-bold">Đăng ký</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
