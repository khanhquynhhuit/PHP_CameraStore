<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg text-indigo-600">
                        <x-application-logo class="block h-8 w-auto fill-current text-indigo-600" />
                        <span>CameraStore</span>
                    </a>
                </div>

                <!-- Navigation Links (Tự động đổi theo Role) -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Trang chủ
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        Sản phẩm
                    </x-nav-link>

                    @auth
                        @if (Auth::user()->isCustomer())
                            <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                                Giỏ hàng
                            </x-nav-link>
                            <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                                Đơn hàng của tôi
                            </x-nav-link>
                        @endif

                        @if (Auth::user()->isAdmin() || Auth::user()->isStaff())
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                Bảng điều khiển
                            </x-nav-link>
                            <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                                QL Sản phẩm
                            </x-nav-link>
                            <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                                QL Danh mục
                            </x-nav-link>
                            <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                                QL Đơn hàng
                            </x-nav-link>
                        @endif

                        @if (Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                QL Người dùng
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown / Guest Links -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                                <span>{{ Auth::user()->name }}</span>

                                @if (Auth::user()->isAdmin())
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                        {{ Auth::user()->role->label() }}
                                    </span>
                                @elseif (Auth::user()->isStaff())
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                        {{ Auth::user()->role->label() }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        {{ Auth::user()->role->label() }}
                                    </span>
                                @endif

                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">
                                Tổng quan tài khoản
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                Hồ sơ cá nhân
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Đăng xuất
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Đăng ký
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Trang chủ
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                Sản phẩm
            </x-responsive-nav-link>

            @auth
                @if (Auth::user()->isCustomer())
                    <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                        Giỏ hàng
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                        Đơn hàng của tôi
                    </x-responsive-nav-link>
                @endif

                @if (Auth::user()->isAdmin() || Auth::user()->isStaff())
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Bảng điều khiển
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        QL Sản phẩm
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        QL Danh mục
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                        QL Đơn hàng
                    </x-responsive-nav-link>
                @endif

                @if (Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        QL Người dùng
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }} ({{ Auth::user()->role->label() }})</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')">
                        Tổng quan tài khoản
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Hồ sơ cá nhân
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Đăng xuất
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        Đăng nhập
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        Đăng ký
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
