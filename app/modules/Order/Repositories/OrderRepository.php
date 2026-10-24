<?php

namespace App\Modules\Order\Repositories;

use App\Models\Order;

class OrderRepository
{
    /**
     * Nơi viết các truy vấn Database liên quan đến đơn hàng
     */
    public function getOrdersByUserId(int|string $userId, array $filters = [])
    {
        // Thêm query() để VS Code nhận diện đúng chuẩn Eloquent Builder, hết báo vàng
        $query = Order::query()->with(['items.product', 'statusHistories'])->where('user_id', $userId);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(10);
    }

    public function getAll(array $filters = [])
    {
        $query = Order::query()->with(['user', 'items', 'statusHistories.changer']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(15);
    }

    public function findById(int|string $id)
    {
        return Order::query()->with(['user', 'items.product', 'statusHistories.changer'])->findOrFail($id);
    }

    public function create(array $orderData, array $itemsData = [])
    {
        $order = Order::create($orderData);

        if (! empty($itemsData)) {
            $order->items()->createMany($itemsData);
        }

        return $order;
    }

    public function update(Order|int|string $order, array $data)
    {
        if (! $order instanceof Order) {
            $order = Order::findOrFail($order);
        }

        $order->update($data);

        return $order;
    }
}
