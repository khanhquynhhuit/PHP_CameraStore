<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-semibold tracking-tight text-xl text-ink leading-tight">
            {{ __('Chi tiết Đơn hàng #' . $order->code) }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-ink/70 hover:text-ink">
                ← Quay lại danh sách đơn hàng
            </a>

            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                    @if($order->status->value === 'completed') bg-emerald-100 text-emerald-800
                    @elseif($order->status->value === 'cancelled') bg-rose-100 text-rose-800
                    @elseif($order->status->value === 'shipping') bg-blue-100 text-blue-800
                    @else bg-amber-100 text-amber-800 @endif">
                    {{ $order->status->label() }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Cột trái: Thông tin đơn & Sản phẩm --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Chi tiết sản phẩm --}}
                <div class="bg-surface border border-ink/10 rounded-xl p-6 shadow-sm">
                    <h3 class="font-heading font-semibold text-base text-ink mb-4">Sản phẩm trong đơn hàng</h3>
                    
                    <div class="divide-y divide-ink/10">
                        @foreach ($order->orderItems as $item)
                            <div class="py-3 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-sand/30 rounded-lg border border-ink/10 flex items-center justify-center overflow-hidden shrink-0">
                                        @if($item->product && $item->product->productImages->first())
                                            <img src="{{ asset('storage/' . $item->product->productImages->first()->image_url) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs text-ink/40">No img</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-ink text-sm">{{ $item->product_name }}</p>
                                        <p class="text-xs text-ink/60">Số lượng: {{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }} đ</p>
                                    </div>
                                </div>
                                <div class="text-sm font-bold text-ink">
                                    {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }} đ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-ink/10 pt-4 mt-4 space-y-2 text-sm text-right">
                        <p class="text-ink/70">Tạm tính: <span class="font-medium text-ink">{{ number_format($order->subtotal, 0, ',', '.') }} đ</span></p>
                        @if($order->discount > 0)
                            <p class="text-emerald-600">Giảm giá: -{{ number_format($order->discount, 0, ',', '.') }} đ</p>
                        @endif
                        <p class="text-ink/70">Phí giao hàng: <span class="font-medium text-ink">{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</span></p>
                        <p class="text-base font-bold text-primary pt-1">Tổng cộng: {{ number_format($order->total, 0, ',', '.') }} đ</p>
                    </div>
                </div>

                {{-- Lịch sử chuyển trạng thái --}}
                <div class="bg-surface border border-ink/10 rounded-xl p-6 shadow-sm">
                    <h3 class="font-heading font-semibold text-base text-ink mb-4">Lịch sử xử lý đơn hàng</h3>
                    <div class="space-y-4">
                        @forelse ($order->statusHistories as $history)
                            <div class="flex items-start gap-3 text-sm">
                                <div class="w-2 h-2 rounded-full bg-primary mt-1.5 shrink-0"></div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-ink">
                                            @if($history->from_status)
                                                {{ \App\Models\Enums\OrderStatus::from($history->from_status)->label() }}
                                                →
                                            @endif
                                            {{ \App\Models\Enums\OrderStatus::from($history->to_status)->label() }}
                                        </p>
                                        <time class="text-xs text-ink/50">{{ $history->created_at->format('d/m/Y H:i') }}</time>
                                    </div>
                                    <p class="text-xs text-ink/70 mt-0.5">Thực hiện bởi: {{ $history->user?->name ?? 'Hệ thống' }}</p>
                                    @if($history->note)
                                        <p class="text-xs bg-sand/30 p-2 rounded mt-1 text-ink/80">{{ $history->note }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-ink/60">Chưa có lịch sử trạng thái.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Cột phải: Form Phê duyệt trạng thái & Thông tin giao hàng --}}
            <div class="space-y-6">
                {{-- Form Phê duyệt trạng thái --}}
                <div class="bg-surface border border-primary/20 rounded-xl p-6 shadow-sm">
                    <h3 class="font-heading font-semibold text-base text-ink mb-1">Cập nhật / Phê duyệt</h3>
                    <p class="text-xs text-ink/60 mb-4">Chuyển trạng thái đơn hàng theo đúng quy trình</p>

                    @php
                        $nextAllowed = $order->status->allowedNext();
                    @endphp

                    @if (empty($nextAllowed))
                        <div class="p-3 bg-sand/40 rounded-lg text-sm text-ink/70">
                            Đơn hàng này đã ở trạng thái kết thúc ({{ $order->status->label() }}), không thể cập nhật thêm.
                        </div>
                    @else
                        <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="status" class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1.5">
                                    Trạng thái tiếp theo
                                </label>
                                <select id="status" name="status" class="w-full text-sm rounded-lg border-ink/20 focus:border-primary focus:ring-primary" required>
                                    @foreach ($nextAllowed as $status)
                                        <option value="{{ $status->value }}">
                                            → {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="note" class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1.5">
                                    Ghi chú nội bộ
                                </label>
                                <textarea id="note" name="note" rows="3" class="w-full text-sm rounded-lg border-ink/20 focus:border-primary focus:ring-primary" placeholder="Nhập ghi chú hoặc lý do thay đổi..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-2.5 px-4 bg-primary text-cream font-semibold text-sm rounded-lg hover:brightness-95 transition shadow-sm">
                                Xác nhận chuyển trạng thái
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Thông tin khách hàng & Giao hàng --}}
                <div class="bg-surface border border-ink/10 rounded-xl p-6 shadow-sm text-sm space-y-4">
                    <h3 class="font-heading font-semibold text-base text-ink">Thông tin nhận hàng</h3>
                    
                    <div>
                        <span class="text-xs text-ink/50 uppercase font-semibold">Khách hàng</span>
                        <p class="font-medium text-ink mt-0.5">{{ $order->receiver_name }}</p>
                        <p class="text-ink/70">{{ $order->phone }}</p>
                    </div>

                    <div>
                        <span class="text-xs text-ink/50 uppercase font-semibold">Địa chỉ giao hàng</span>
                        <p class="text-ink/80 mt-0.5">{{ $order->shipping_address }}</p>
                    </div>

                    <div>
                        <span class="text-xs text-ink/50 uppercase font-semibold">Phương thức & Thanh toán</span>
                        <p class="font-medium text-ink mt-0.5">{{ $order->payment_method->label() }}</p>
                        <p class="text-xs text-ink/70 mt-0.5">Trạng thái: {{ $order->payment_status->label() }}</p>
                    </div>

                    @if($order->note)
                        <div>
                            <span class="text-xs text-ink/50 uppercase font-semibold">Ghi chú từ khách hàng</span>
                            <p class="text-xs italic text-ink/70 mt-0.5 bg-sand/30 p-2 rounded">{{ $order->note }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
