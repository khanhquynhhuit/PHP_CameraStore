<?php

namespace App\Modules\Order\Services;

use App\Modules\Order\Repositories\OrderRepository;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository
    ) {}

    public function listCustomerOrders(int|string $userId, array $filters = [])
    {
        // TODO: Viết logic lấy danh sách đơn hàng của khách hàng (Tâm)
        return collect();
    }

    public function listAllOrders(array $filters = [])
    {
        // TODO: Viết logic lấy danh sách đơn hàng toàn hệ thống (Tâm)
        return collect();
    }

    public function getOrderDetail(int|string $id)
    {
        // TODO: Viết logic lấy chi tiết đơn hàng (Tâm)
        return null;
    }

    public function checkout(int|string $userId, array $data)
    {
        // TODO: Viết logic đặt hàng trong DB::transaction, trừ tồn kho và xóa giỏ hàng (Tâm)
    }
}
