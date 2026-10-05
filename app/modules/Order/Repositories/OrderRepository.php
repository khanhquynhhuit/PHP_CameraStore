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
        // TODO: Viết query lấy danh sách đơn hàng của khách hàng
    }

    public function getAll(array $filters = [])
    {
        // TODO: Viết query lấy toàn bộ đơn hàng (Admin/Staff)
    }

    public function findById(int|string $id)
    {
        // TODO: Viết query tìm đơn hàng theo ID
    }

    public function create(array $orderData, array $itemsData = [])
    {
        // TODO: Viết query tạo mới đơn hàng và chi tiết đơn hàng
    }

    public function update(Order|int|string $order, array $data)
    {
        // TODO: Viết query cập nhật thông tin đơn hàng
    }
}
