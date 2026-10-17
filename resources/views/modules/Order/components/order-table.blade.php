<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-ink/10">
        <thead class="bg-surface">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Mã đơn</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Ngày đặt</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Tổng tiền</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Trạng thái</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-ink/70 uppercase tracking-wider">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-base divide-y divide-ink/10">
            @forelse ($orders as $order)
                <tr class="hover:bg-ink/[0.02] transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary">
                        <a href="{{ route('orders.show', $order->id) }}" class="hover:underline">
                            {{ $order->code }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-ink/70">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-ink">
                        {{ number_format($order->total, 0, ',', '.') }} đ
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            @if($order->status->value === 'completed') bg-emerald-100 text-emerald-800
                            @elseif($order->status->value === 'cancelled') bg-rose-100 text-rose-800
                            @elseif($order->status->value === 'shipping') bg-blue-100 text-blue-800
                            @else bg-amber-100 text-amber-800 @endif">
                            {{ $order->status->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('orders.show', $order->id) }}" class="text-primary hover:text-primary/80 font-semibold mr-3">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-sm text-ink/60">
                        Bạn chưa có đơn hàng nào.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($orders->hasPages())
        <div class="p-4 border-t border-ink/10">
            {{ $orders->links() }}
        </div>
    @endif
</div>
