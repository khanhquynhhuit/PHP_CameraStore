@props(['product'])

<div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition bg-white p-4">
    <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-md mb-4 flex items-center justify-center">
        <span class="text-gray-400 text-sm">Hình ảnh máy ảnh</span>
    </div>
    <h3 class="font-semibold text-gray-900 truncate">{{ $product->name ?? 'Tên sản phẩm' }}</h3>
    <p class="text-indigo-600 font-bold mt-2">{{ number_format($product->price ?? 0) }} đ</p>
    <div class="mt-4 flex gap-2">
        <a href="#" class="w-full text-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded text-sm hover:bg-indigo-100">Chi tiết</a>
        <button type="button" class="px-3 py-1.5 bg-gray-900 text-white rounded text-sm hover:bg-gray-800">Thêm giỏ</button>
    </div>
</div>
