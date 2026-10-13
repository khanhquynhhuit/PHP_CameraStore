<nav x-data="{ open: false }" class="bg-base/95 backdrop-blur border-b border-beige/50 sticky top-0 z-40 shadow-xs font-sans text-ink">
    <!-- Top Bar (Hotline & Khuyến mãi CameraStore.vn) -->
    <div class="bg-sand text-ink/90 border-b border-beige/40 text-xs py-1.5 px-4 hidden sm:block">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5">
                    <x-phosphor-fire-light class="w-4 h-4 text-primary" />
                    <strong class="font-semibold text-ink">Hotline tư vấn máy ảnh:</strong> 
                    <a href="tel:19006789" class="font-heading font-bold text-primary-dark underline">1900 6789</a>
                </span>
                <span class="text-ink/20">|</span>
                <span class="text-ink/75">Hệ thống Showroom: Hà Nội - Đà Nẵng - TP. Hồ Chí Minh</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="px-2 py-0.5 rounded bg-accent text-ink font-heading font-semibold shadow-2xs">Trả góp 0% lãi suất</span>
                <span class="text-ink/20">|</span>
                <span class="text-ink/75">Bảo hành chính hãng toàn quốc</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20 gap-4 sm:gap-8">
            <!-- Brand Logo (CameraStore.vn) -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="CameraStore.vn" 
                         class="w-12 h-12 object-contain group-hover:scale-105 transition-transform duration-200">
                    <div class="flex flex-col">
                        <span class="text-2xl font-heading font-bold tracking-tight text-ink leading-none">
                            CAMERASTORE<span class="text-primary">.VN</span>
                        </span>
                        <span class="text-[9px] uppercase tracking-widest text-ink/65 font-bold mt-1">Máy Ảnh & Thiết Bị Số • Est. 2012</span>
                    </div>
                </a>
            </div>

            <!-- Thanh tìm kiếm trung tâm lớn CameraStore.vn -->
            <div class="flex-1 max-w-xl hidden md:block">
                <form action="{{ route('products.index') }}" method="GET" class="relative">
                    <input type="text" 
                           name="keyword" 
                           placeholder="Bạn cần tìm máy ảnh, ống kính Sony, Canon, Fujifilm..." 
                           class="w-full pl-4 pr-28 py-2.5 rounded-lg bg-surface border border-beige/70 text-ink placeholder-ink/40 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                    <button type="submit" 
                            class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-primary hover:bg-primary-dark text-cream text-xs font-heading font-semibold rounded-md transition flex items-center gap-1.5 shadow-sm">
                        <x-phosphor-magnifying-glass-light class="w-4 h-4 text-cream" />
                        <span>Tìm kiếm</span>
                    </button>
                </form>
            </div>

            <!-- Right Actions (Showroom, Giỏ hàng & User) -->
            <div class="hidden sm:flex items-center gap-4">
                <!-- Mini Cart Dropdown -->
                <x-mini-cart />

                @auth
                    @if (Auth::user()->isAdmin() || Auth::user()->isStaff())
                        <x-button variant="outline" size="sm" :href="route('dashboard')">
                            <x-phosphor-gear-light class="w-4 h-4 text-primary" />
                            <span>Vào trang Quản trị</span>
                        </x-button>
                    @endif

                    <!-- Settings Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-beige/60 text-xs font-semibold rounded-lg text-ink bg-surface hover:bg-sand/40 focus:outline-none transition">
                                <div class="text-left">
                                    <span class="text-ink/65 block text-[10px]">Xin chào,</span>
                                    <span class="font-bold text-ink">{{ Auth::user()->name }}</span>
                                </div>

                                @if (Auth::user()->isAdmin())
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-danger text-danger-ink">
                                        {{ Auth::user()->role->label() }}
                                    </span>
                                @elseif (Auth::user()->isStaff())
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-navy text-cream">
                                        {{ Auth::user()->role->label() }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-accent text-ink">
                                        {{ Auth::user()->role->label() }}
                                    </span>
                                @endif

                                <div>
                                    <svg class="fill-current h-4 w-4 text-ink/60" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">
                                {{ Auth::user()->isCustomer() ? __('Tổng quan tài khoản') : __('Bảng điều khiển Quản trị') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Hồ sơ cá nhân') }}
                            </x-dropdown-link>

                            @if (Auth::user()->isCustomer())
                                <x-dropdown-link :href="route('orders.index')">
                                    {{ __('Đơn hàng của tôi') }}
                                </x-dropdown-link>
                            @endif

                            <!-- Logout -->
                            <div class="border-t border-beige/40 my-1"></div>
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
                        <x-button variant="outline" size="sm" :href="route('login')">
                            Đăng nhập
                        </x-button>
                        <x-button variant="primary" size="sm" :href="route('register')">
                            Đăng ký
                        </x-button>
                    </div>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="flex items-center sm:hidden gap-2">
                <a href="{{ route('cart.index') }}" class="relative p-2 text-ink">
                    <x-phosphor-shopping-cart-light class="w-6 h-6 text-primary" />
                </a>

                <button @click="open = ! open" class="p-2 rounded-lg text-ink hover:bg-sand/60 focus:outline-none">
                    <x-phosphor-list-light ::class="{'hidden': open, 'block': ! open }" class="h-6 w-6" />
                    <x-phosphor-x-light ::class="{'hidden': ! open, 'block': open }" class="h-6 w-6 hidden" />
                </button>
            </div>
        </div>

        <!-- Sub Mega Menu Bar (CameraStore.vn) -->
        <div class="hidden sm:flex items-center gap-6 py-2.5 border-t border-beige/40 text-sm font-semibold text-ink/80">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-cream font-bold bg-primary px-2.5 py-1 rounded-lg' : 'hover:text-primary-dark' }} flex items-center gap-1.5 transition">
                <x-phosphor-house-light class="w-4 h-4 {{ request()->routeIs('home') ? 'text-cream' : 'text-ink/70' }}" />
                Trang chủ
            </a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'text-cream font-bold bg-primary px-2.5 py-1 rounded-lg' : 'hover:text-primary-dark' }} transition">
                Máy Ảnh Chuyên Nghiệp
            </a>
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'text-cream font-bold bg-primary px-2.5 py-1 rounded-lg' : 'hover:text-primary-dark' }} transition">
                Danh Mục Thiết Bị
            </a>
            <a href="{{ route('products.index', ['brand' => 'Sony']) }}" class="hover:text-primary-dark transition">
                Sony Alpha
            </a>
            <a href="{{ route('products.index', ['brand' => 'Canon']) }}" class="hover:text-primary-dark transition">
                Canon EOS
            </a>
            <a href="{{ route('products.index', ['brand' => 'Fujifilm']) }}" class="hover:text-primary-dark transition">
                Fujifilm X
            </a>

            <span class="ms-auto text-xs text-ink font-heading font-semibold bg-accent px-2.5 py-1 rounded-lg inline-flex items-center gap-1.5 shadow-2xs">
                <x-phosphor-lightning-light class="w-3.5 h-3.5 text-ink" />
                Flash Sale Giảm Đến 30%
            </span>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-beige/40 bg-base px-4 py-3">
        <!-- Search bar mobile -->
        <form action="{{ route('products.index') }}" method="GET" class="relative mb-3">
            <input type="text" name="keyword" placeholder="Tìm kiếm máy ảnh..." class="w-full px-3 py-2 text-sm bg-surface border border-beige/60 text-ink placeholder-ink/40 rounded-lg pr-10 focus:border-primary focus:ring-primary">
            <button type="submit" class="absolute right-2 top-2 text-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </form>

        <div class="space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Trang chủ</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">Tất cả sản phẩm</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">Danh mục thiết bị</x-responsive-nav-link>

            @auth
                <div class="px-4 py-2 border-t border-beige/40 mt-2">
                    <div class="font-medium text-base text-ink">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-ink/70">{{ Auth::user()->email }} ({{ Auth::user()->role->label() }})</div>
                </div>

                @if (Auth::user()->isCustomer())
                    <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">Giỏ hàng</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">Đơn hàng của tôi</x-responsive-nav-link>
                @endif

                @if (Auth::user()->isAdmin() || Auth::user()->isStaff())
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Bảng điều khiển</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">QL Sản phẩm</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">QL Danh mục</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">QL Đơn hàng</x-responsive-nav-link>
                @endif

                @if (Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">QL Người dùng</x-responsive-nav-link>
                @endif

                <x-responsive-nav-link :href="route('dashboard')">Tổng quan tài khoản</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">Hồ sơ cá nhân</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        Đăng xuất
                    </x-responsive-nav-link>
                </form>
            @else
                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">Giỏ hàng</x-responsive-nav-link>
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <x-button variant="outline" size="sm" :href="route('login')" class="w-full">Đăng nhập</x-button>
                    <x-button variant="primary" size="sm" :href="route('register')" class="w-full">Đăng ký</x-button>
                </div>
            @endauth
        </div>
    </div>
</nav>
