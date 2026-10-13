<div class="divide-y divide-ink/10">
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
                         class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg bg-base border border-ink/10 shadow-sm"
                         onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Camera';">
                </a>
                <div>
                    <a href="{{ route('products.show', $item->product->id) }}" class="font-heading font-semibold tracking-tight text-ink hover:underline transition text-sm sm:text-base line-clamp-2">
                        {{ $item->product->name }}
                    </a>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-ink/70">Mã: {{ $item->product->sku ?? 'N/A' }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $item->product->stock > 5 ? 'bg-success text-success-ink font-semibold' : 'bg-danger text-danger-ink font-semibold' }}">
                            Còn {{ $item->product->stock }} máy
                        </span>
                    </div>
                    <p class="font-heading text-sm font-bold tabular-nums text-ink mt-1">
                        {{ number_format($item->product->price, 0, ',', '.') }} đ
                    </p>
                </div>
            </div>

            <!-- Tăng giảm số lượng và Nút Xóa -->
            <div class="flex items-center justify-between sm:justify-end gap-4 border-t border-ink/10 sm:border-t-0 pt-2 sm:pt-0">
                <!-- Form cập nhật số lượng -->
                <div class="flex items-center border border-ink/20 rounded-lg overflow-hidden bg-surface">
                    <!-- Nút giảm -->
                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                        <button type="submit" 
                                class="px-2.5 py-1 text-ink/70 hover:bg-primary/20 hover:text-ink transition font-bold"
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
                               class="w-14 text-center border-0 bg-transparent text-ink text-sm font-medium tabular-nums focus:ring-1 focus:ring-primary py-1">
                    </form>

                    <!-- Nút tăng -->
                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                        <button type="submit" 
                                class="px-2.5 py-1 text-ink/70 hover:bg-primary/20 hover:text-ink transition font-bold"
                                {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                            +
                        </button>
                    </form>
                </div>

                <!-- Thành tiền của dòng -->
                <div class="text-right sm:min-w-[110px]">
                    <span class="block text-xs text-ink/70">Thành tiền</span>
                    <span class="font-heading font-bold tabular-nums text-ink text-sm sm:text-base">
                        {{ number_format($item->line_total, 0, ',', '.') }} đ
                    </span>
                </div>

                <!-- Nút xóa sản phẩm -->
                <form method="POST" action="{{ route('cart.destroy', $item->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ?')"
                            class="text-ink/40 hover:text-danger-ink p-1 rounded-full hover:bg-danger/20 transition" 
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
            <svg class="mx-auto h-16 w-16 text-ink/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p class="text-sm text-ink/70 mb-4">Giỏ hàng của bạn đang trống</p>
            <x-button variant="primary" size="md" :href="route('products.index')" class="uppercase tracking-widest">
                Khám phá sản phẩm ngay
            </x-button>
        </div>
    @endforelse
</div>
