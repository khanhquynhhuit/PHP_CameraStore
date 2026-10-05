                            <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Không có quyền truy cập | Camera Store</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1 class="text-6xl font-extrabold text-gray-900 mb-2">403</h1>
        <h2 class="text-xl font-bold text-gray-800 mb-2">Truy cập bị từ chối</h2>
        <p class="text-gray-500 text-sm mb-6">{{ $message ?? 'Bạn không có đủ quyền để truy cập vào tài nguyên này.' }}</p>
        <div class="flex gap-3 justify-center">
            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm transition">Quay lại</a>
            <a href="{{ url('/') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm transition">Trang chủ</a>
        </div>
    </div>
</body>
</html>
