<?php

namespace App\Modules\Dashboard\Repositories;

use App\Models\Enums\OrderStatus;
use App\Models\Enums\ProductStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Collection;

class DashboardRepository
{
    public function getTotalRevenue(): float
    {
        return (float) Order::query()
            ->where('status', OrderStatus::COMPLETED->value)
            ->sum('total');
    }

    public function getTodayRevenue(): float
    {
        return (float) Order::query()
            ->where('status', OrderStatus::COMPLETED->value)
            ->whereDate('created_at', today())
            ->sum('total');
    }

    public function getCurrentMonthRevenue(): float
    {
        return (float) Order::query()
            ->where('status', OrderStatus::COMPLETED->value)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');
    }

    public function countCompletedOrders(): int
    {
        return Order::query()
            ->where('status', OrderStatus::COMPLETED->value)
            ->count();
    }

    public function countPendingOrders(): int
    {
        return Order::query()
            ->where('status', OrderStatus::PENDING->value)
            ->count();
    }

    public function getLowStockProducts(int $limit = 10): Collection
    {
        return Product::query()
            ->with(['category', 'brand'])
            ->where('status', ProductStatus::ACTIVE->value)
            ->where('stock', '<', 5)
            ->orderBy('stock')
            ->limit($limit)
            ->get();
    }

    public function getBestSellingProducts(int $limit = 5): Collection
    {
        return OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', OrderStatus::COMPLETED->value)
            ->select([
                'order_items.product_id',
                'order_items.product_name',
            ])
            ->selectRaw('SUM(order_items.quantity) AS total_sold')
            ->selectRaw(
                'SUM(order_items.unit_price * order_items.quantity) AS revenue'
            )
            ->groupBy(
                'order_items.product_id',
                'order_items.product_name'
            )
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
}