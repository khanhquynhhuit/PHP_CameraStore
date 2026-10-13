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
    <body class="font-sans text-base text-ink antialiased bg-base">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex flex-col items-center gap-2.5 group">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="CameraStore.vn" 
                         class="w-16 h-16 object-contain group-hover:scale-105 transition-transform duration-200">
                    <span class="text-2xl font-heading font-bold tracking-tight text-ink">
                        CAMERASTORE<span class="text-primary">.VN</span>
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-base border border-ink/10 shadow-sm overflow-hidden sm:rounded-2xl">
                <x-alert />
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
