<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Google Fonts: Be Vietnam Pro & Inter (Chuẩn tiếng Việt 100%) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-base antialiased bg-base text-ink">
        @php
            $isAdminWorkspace = auth()->check()
                && (auth()->user()->isAdmin() || auth()->user()->isStaff())
                && request()->routeIs('dashboard', 'admin.*', 'profile.*');
        @endphp

        @if ($isAdminWorkspace)
            {{-- GIAO DIỆN QUẢN TRỊ RIÊNG CHO ADMIN & NHÂN VIÊN (Không dính Header/Footer bán hàng của Khách) --}}
            @include('layouts.admin-sidebar')
        @else
            {{-- GIAO DIỆN CỬA HÀNG DÀNH CHO KHÁCH HÀNG (CAMERASTORE.VN) --}}
            <div class="min-h-screen bg-base text-ink flex flex-col justify-between">
                <div>
                    @include('layouts.navigation')

                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-surface border-b border-ink/10">
                            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main>
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                            <x-alert />
                        </div>
                        {{ $slot }}
                    </main>
                </div>

                @include('layouts.footer')
            </div>
        @endif
    </body>
</html>
