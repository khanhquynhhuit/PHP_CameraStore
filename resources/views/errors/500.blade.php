<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Lỗi máy chủ | Camera Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-base antialiased bg-surface text-ink flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center bg-base p-8 rounded-2xl shadow-sm border border-ink/10">
        <div class="w-16 h-16 bg-surface text-ink rounded-full flex items-center justify-center mx-auto mb-4 border border-ink/10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h1 class="font-heading text-6xl font-bold tabular-nums tracking-tight text-ink mb-2">500</h1>
        <h2 class="font-heading text-xl font-semibold tracking-tight text-ink mb-2">Sự cố máy chủ</h2>
        <p class="text-ink/70 text-sm mb-6">{{ $message ?? 'Hệ thống đang gặp sự cố tạm thời. Vui lòng thử lại sau.' }}</p>
        <div class="flex gap-3 justify-center">
            <x-button variant="secondary" size="md" :href="url()->previous()">Quay lại</x-button>
            <x-button variant="primary" size="md" :href="url('/')">Trang chủ</x-button>
        </div>
    </div>
</body>
</html>
