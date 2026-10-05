<?php

namespace App\Modules\Order\Services;

use App\Modules\Order\Repositories\OrderRepository;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository
    ) {}

    /**
     * Nơi viết các logic nghiệp vụ liên quan đến đơn hàng
     */
    public function listCustomerOrders(int|string $userId, array $filters = [])
    {
        // TODO: Viết logic lấy danh sách đơn hàng của khách hàng
    }

    public function listAllOrders(array $filters = [])
    {
        // TODO: Viết logic lấy danh sách đơn hàng cho admin
    }

    public function getOrderDetail(int|string $id)
    {
        // TODO: Viết logic lấy chi tiết đơn hàng
    }

    public function checkout(int|string $userId, array $data)
    {
        // TODO: Viết logic nghiệp vụ đặt hàng (tính tiền, áp coupon, trừ kho, tạo đơn)
    }
}
