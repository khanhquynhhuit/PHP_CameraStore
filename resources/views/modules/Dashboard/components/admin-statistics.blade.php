@if ($dashboardData)
    <div class="space-y-6">
        {{-- KPI --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
            <div class="bg-surface border border-ink/10 rounded-xl p-5">
                <p class="text-sm text-ink/60">Tổng doanh thu</p>
                <p class="mt-2 text-xl font-bold text-primary">
                    {{ number_format(
                        (float) $dashboardData['stats']['total_revenue'],
                        0,
                        ',',
                        '.'
                    ) }} đ
                </p>
            </div>

            <div class="bg-surface border border-ink/10 rounded-xl p-5">
                <p class="text-sm text-ink/60">Doanh thu hôm nay</p>
                <p class="mt-2 text-xl font-bold text-primary">
                    {{ number_format(
                        (float) $dashboardData['stats']['today_revenue'],
                        0,
                        ',',
                        '.'
                    ) }} đ
                </p>
            </div>

            <div class="bg-surface border border-ink/10 rounded-xl p-5">
                <p class="text-sm text-ink/60">Doanh thu tháng này</p>
                <p class="mt-2 text-xl font-bold text-primary">
                    {{ number_format(
                        (float) $dashboardData['stats']['month_revenue'],
                        0,
                        ',',
                        '.'
                    ) }} đ
                </p>
            </div>

            <div class="bg-surface border border-ink/10 rounded-xl p-5">
                <p class="text-sm text-ink/60">Đơn đã hoàn thành</p>
                <p class="mt-2 text-3xl font-bold text-success-ink">
                    {{ $dashboardData['stats']['completed_orders'] }}
                </p>
            </div>

            <div class="bg-surface border border-ink/10 rounded-xl p-5">
                <p class="text-sm text-ink/60">Đơn chờ duyệt</p>
                <p class="mt-2 text-3xl font-bold text-warning-ink">
                    {{ $dashboardData['stats']['pending_orders'] }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            {{-- Sản phẩm sắp hết hàng --}}
            <div class="bg-surface border border-ink/10 rounded-xl p-5">
                <h3 class="font-heading font-semibold text-lg text-ink mb-4">
                    Cảnh báo tồn kho
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-ink/10 text-left">
                                <th class="py-3">Sản phẩm</th>
                                <th class="py-3">Thương hiệu</th>
                                <th class="py-3 text-right">Tồn kho</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse (
                                $dashboardData['low_stock_products']
                                as $product
                            )
                                <tr class="border-b border-ink/5">
                                    <td class="py-3">
                                        {{ $product->name }}
                                    </td>

                                    <td class="py-3">
                                        {{ $product->brand?->name ?? 'N/A' }}
                                    </td>

                                    <td class="py-3 text-right font-bold
                                        {{ $product->stock === 0
                                            ? 'text-danger-ink'
                                            : 'text-warning-ink' }}">
                                        {{ $product->stock }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="py-6 text-center text-ink/60">
                                        Không có sản phẩm sắp hết hàng.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Sản phẩm bán chạy --}}
            <div class="bg-surface border border-ink/10 rounded-xl p-5">
                <h3 class="font-heading font-semibold text-lg text-ink mb-4">
                    Sản phẩm bán chạy
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-ink/10 text-left">
                                <th class="py-3">Sản phẩm</th>
                                <th class="py-3 text-right">Đã bán</th>
                                <th class="py-3 text-right">Doanh thu</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse (
                                $dashboardData['best_selling_products']
                                as $product
                            )
                                <tr class="border-b border-ink/5">
                                    <td class="py-3">
                                        {{ $product->product_name }}
                                    </td>

                                    <td class="py-3 text-right font-bold">
                                        {{ $product->total_sold }}
                                    </td>

                                    <td class="py-3 text-right">
                                        {{ number_format(
                                            (float) $product->revenue,
                                            0,
                                            ',',
                                            '.'
                                        ) }} đ
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="py-6 text-center text-ink/60">
                                        Chưa có dữ liệu bán hàng hoàn tất.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif