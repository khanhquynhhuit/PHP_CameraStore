                            <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Không có quyền truy cập | Camera Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-base antialiased bg-surface text-ink flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center bg-base p-8 rounded-2xl shadow-sm border border-ink/10">
        <div class="w-16 h-16 bg-danger text-danger-ink rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1 class="font-heading text-6xl font-bold tabular-nums tracking-tight text-ink mb-2">403</h1>
        <h2 class="font-heading text-xl font-semibold tracking-tight text-ink mb-2">Truy cập bị từ chối</h2>
        <p class="text-ink/70 text-sm mb-6">{{ $message ?? 'Bạn không có đủ quyền để truy cập vào tài nguyên này.' }}</p>
        <div class="flex gap-3 justify-center">
            <x-button variant="secondary" size="md" :href="url()->previous()">Quay lại trang trước</x-button>
            @auth
                @if (auth()->user()->isAdmin() || auth()->user()->isStaff())
                    <x-button variant="primary" size="md" :href="route('dashboard')">Về Bảng điều khiển</x-button>
                @else
                    <x-button variant="primary" size="md" :href="route('home')">Về Trang chủ mua sắm</x-button>
                @endif
            @else
                <x-button variant="primary" size="md" :href="route('home')">Về Trang chủ</x-button>
            @endauth
        </div>
    </div>
</body>
</html>
