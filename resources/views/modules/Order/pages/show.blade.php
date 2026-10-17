<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-semibold tracking-tight text-xl text-ink leading-tight">
            {{ __('Chi tiết đơn hàng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-surface border border-ink/10 overflow-hidden shadow-sm sm:rounded-xl p-6">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-ink/10 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-ink">Đơn hàng #{{ $order->code }}</h3>
                        <p class="text-xs text-ink/60 mt-1">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            @if($order->status->value === 'completed') bg-emerald-100 text-emerald-800
                            @elseif($order->status->value === 'cancelled') bg-rose-100 text-rose-800
                            @elseif($order->status->value === 'shipping') bg-blue-100 text-blue-800
                            @else bg-amber-100 text-amber-800 @endif">
                            {{ $order->status->label() }}
                        </span>

                        @if (in_array($order->status->value, ['pending', 'confirmed', 'shipping'], true))
                            <form method="POST" action="{{ route('orders.received', $order->id) }}" onsubmit="return confirm('Bạn xác nhận đã nhận được đủ hàng và hài lòng với sản phẩm?');">
                                @csrf
                                <button type="submit" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                                    <span>✓</span>
                                    <span>Đã nhận được hàng</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Thông tin giao hàng --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4 border-b border-ink/10 text-sm">
                    <div>
                        <p class="text-xs font-semibold uppercase text-ink/50 tracking-wider">Người nhận</p>
                        <p class="font-medium text-ink mt-1">{{ $order->receiver_name }} - {{ $order->phone }}</p>
                        <p class="text-ink/70 mt-1">{{ $order->shipping_address }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-ink/50 tracking-wider">Phương thức thanh toán</p>
                        <p class="font-medium text-ink mt-1">{{ $order->payment_method->label() }}</p>
                        <p class="text-xs text-ink/60 mt-1">Trạng thái: {{ $order->payment_status->label() }}</p>
                    </div>
                </div>

                {{-- Danh sách sản phẩm --}}
                <div class="py-4">
                    <p class="text-xs font-semibold uppercase text-ink/50 tracking-wider mb-3">Sản phẩm trong đơn</p>
                    <div class="divide-y divide-ink/10">
                        @foreach ($order->orderItems as $item)
                            <div class="py-3 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-base rounded-lg border border-ink/10 flex items-center justify-center overflow-hidden shrink-0">
                                        @if($item->product && $item->product->productImages->first())
                                            <img src="{{ asset('storage/' . $item->product->productImages->first()->image_url) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs text-ink/40">No img</span>
                                        @endif
                                    </div>
                                    <div>
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product->id) }}" class="font-medium text-ink hover:text-primary hover:underline">
                                                {{ $item->product_name }}
                                            </a>
                                        @else
                                            <span class="font-medium text-ink">{{ $item->product_name }}</span>
                                        @endif
                                        <p class="text-xs text-ink/60">Số lượng: {{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }} đ</p>
                                    </div>
                                </div>
                                <div class="text-right flex flex-col items-end gap-1">
                                    <span class="font-semibold text-ink text-sm">
                                        {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }} đ
                                    </span>
                                    @if($order->status->value === 'completed' && $item->product)
                                        <a href="{{ route('products.show', $item->product->id) }}#reviews" class="inline-flex items-center text-xs font-semibold text-primary hover:underline bg-primary/10 px-2 py-1 rounded">
                                            ★ Đánh giá sản phẩm
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tổng tiền --}}
                <div class="border-t border-ink/10 pt-4 text-right space-y-1 text-sm">
                    <p class="text-ink/70">Tạm tính: <span class="font-medium text-ink">{{ number_format($order->subtotal, 0, ',', '.') }} đ</span></p>
                    @if($order->discount > 0)
                        <p class="text-emerald-600">Giảm giá: -{{ number_format($order->discount, 0, ',', '.') }} đ</p>
                    @endif
                    <p class="text-ink/70">Phí giao hàng: <span class="font-medium text-ink">{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</span></p>
                    <p class="text-base font-bold text-primary pt-2">Tổng cộng: {{ number_format($order->total, 0, ',', '.') }} đ</p>
                </div>
            </div>

            <div class="flex flex-wrap justify-between items-center gap-4">
                <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-ink/70 hover:text-ink">
                    ← Quay lại danh sách đơn hàng
                </a>

                <div class="flex items-center gap-3">
                    {{-- Khách hàng tự hủy đơn khi còn PENDING --}}
                    @if ($order->status->value === 'pending')
                        <form method="POST" action="{{ route('orders.cancel', $order->id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg border border-danger/40 text-danger hover:bg-danger/10 text-sm font-semibold transition">
                                Hủy đơn hàng
                            </button>
                        </form>
                    @endif

                    {{-- Khách hàng xác nhận đã nhận hàng khi đơn CONFIRMED hoặc SHIPPING --}}
                    @if (in_array($order->status->value, ['confirmed', 'shipping'], true))
                        <form method="POST" action="{{ route('orders.received', $order->id) }}" onsubmit="return confirm('Bạn xác nhận đã nhận được đủ hàng và hài lòng với sản phẩm?');">
                            @csrf
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm transition flex items-center gap-2">
                                <span>✓</span>
                                <span>Đã nhận được hàng</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
