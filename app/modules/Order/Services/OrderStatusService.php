<?php

namespace App\Modules\Order\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Modules\Order\Repositories\OrderRepository;

class OrderStatusService
{
    public function __construct(
        protected OrderRepository $orderRepository
    ) {}

    /**
     * Nơi duy nhất được phép xử lý logic chuyển trạng thái đơn hàng (State Machine)
     */
    public function transitionTo(Order|int|string $order, OrderStatus|string $targetStatus, ?int $changedBy = null, ?string $note = null)
    {
        // TODO: Viết logic chuyển trạng thái đơn hàng tuân thủ OrderStatus::canTransitionTo()
    }

    public function cancelOrderByCustomer(Order|int|string $order, int|string $userId, ?string $reason = null)
    {
        // TODO: Viết logic khách hàng hủy đơn khi còn ở trạng thái PENDING
    }
}
