<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-semibold tracking-tight text-xl text-ink leading-tight">
            {{ __('Quản lý Đơn hàng') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold font-heading text-ink">Danh sách đơn hàng</h1>
                <p class="text-sm text-ink/60 mt-1">Quản lý và cập nhật trạng thái đơn hàng của hệ thống</p>
            </div>
        </div>

        <div class="bg-surface border border-ink/10 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-ink/10">
                    <thead class="bg-sand/30">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Mã đơn</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Khách hàng</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Ngày đặt</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Tổng tiền</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Thanh toán</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-ink/70 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-ink/70 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-surface divide-y divide-ink/10">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-sand/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-primary">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                        {{ $order->code }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">
                                    <div class="font-medium">{{ $order->receiver_name }}</div>
                                    <div class="text-xs text-ink/60">{{ $order->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-ink/70">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-ink">
                                    {{ number_format($order->total, 0, ',', '.') }} đ
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $order->payment_status->value === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $order->payment_status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($order->status->value === 'completed') bg-emerald-100 text-emerald-800
                                        @elseif($order->status->value === 'cancelled') bg-rose-100 text-rose-800
                                        @elseif($order->status->value === 'shipping') bg-blue-100 text-blue-800
                                        @else bg-amber-100 text-amber-800 @endif">
                                        {{ $order->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-sand/60 hover:bg-sand text-ink text-xs font-semibold transition">
                                        Chi tiết & Duyệt
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-ink/60">
                                    Chưa có đơn hàng nào trong hệ thống.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="p-4 border-t border-ink/10">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
