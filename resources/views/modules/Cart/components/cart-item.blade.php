<div class="divide-y divide-gray-200">
    @forelse ($items as $item)
        <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Thông tin & Ảnh sản phẩm -->
            <div class="flex items-center gap-4">
                <a href="{{ route('products.show', $item->product->id) }}" class="shrink-0">
                    @php
                        $image = $item->product->primary_image;
                        $imageUrl = $image ? asset('storage/' . $image->image_url) : 'https://placehold.co/100x100?text=Camera';
                    @endphp
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $item->product->name }}" 
                         class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg border border-gray-100 shadow-sm"
                         onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Camera';">
                </a>
                <div>
                    <a href="{{ route('products.show', $item->product->id) }}" class="font-medium text-gray-900 hover:text-indigo-600 transition text-sm sm:text-base line-clamp-2">
                        {{ $item->product->name }}
                    </a>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-gray-500">Mã: {{ $item->product->sku ?? 'N/A' }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $item->product->stock > 5 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            Còn {{ $item->product->stock }} máy
                        </span>
                    </div>
                    <p class="text-sm font-semibold text-indigo-600 mt-1">
                        {{ number_format($item->product->price, 0, ',', '.') }} đ
                    </p>
                </div>
            </div>

            <!-- Tăng giảm số lượng và Nút Xóa -->
            <div class="flex items-center justify-between sm:justify-end gap-4 border-t sm:border-t-0 pt-2 sm:pt-0">
                <!-- Form cập nhật số lượng -->
                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                    <!-- Nút giảm -->
                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                        <button type="submit" 
                                class="px-2.5 py-1 text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition font-bold"
                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                            -
                        </button>
                    </form>

                    <!-- Số lượng hiện tại -->
                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="number" 
                               name="quantity" 
                               value="{{ $item->quantity }}" 
                               min="1" 
                               max="{{ $item->product->stock }}"
                               onchange="this.form.submit()"
                               class="w-14 text-center border-0 bg-transparent text-sm font-medium focus:ring-1 focus:ring-indigo-500 py-1">
                    </form>

                    <!-- Nút tăng -->
                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                        <button type="submit" 
                                class="px-2.5 py-1 text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition font-bold"
                                {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                            +
                        </button>
                    </form>
                </div>

                <!-- Thành tiền của dòng -->
                <div class="text-right sm:min-w-[110px]">
                    <span class="block text-xs text-gray-400">Thành tiền</span>
                    <span class="font-bold text-gray-900 text-sm sm:text-base">
                        {{ number_format($item->line_total, 0, ',', '.') }} đ
                    </span>
                </div>

                <!-- Nút xóa sản phẩm -->
                <form method="POST" action="{{ route('cart.destroy', $item->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ?')"
                            class="text-gray-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50 transition" 
                            title="Xóa sản phẩm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="py-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p class="text-gray-500 text-base mb-4">Giỏ hàng của bạn đang trống</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition ease-in-out duration-150">
                Khám phá sản phẩm ngay
            </a>
        </div>
    @endforelse
</div>
