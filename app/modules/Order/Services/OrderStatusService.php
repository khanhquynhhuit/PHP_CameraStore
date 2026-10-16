<?php

namespace App\Modules\Order\Services;

use App\Models\Enums\OrderStatus;
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
    public function transitionTo(Order|int|string $order, OrderStatus|string $targetStatus, ?int $changedBy = null, ?string $note = null): Order
    {
        $order = $order instanceof Order ? $order : Order::findOrFail($order);

        if (is_string($targetStatus)) {
            $targetStatus = OrderStatus::from($targetStatus);
        }

        $currentStatus = $order->status;

        if ($currentStatus === $targetStatus) {
            return $order;
        }

        if (!$currentStatus->canTransitionTo($targetStatus)) {
            throw new \App\Exceptions\BusinessException(
                "Không thể chuyển trạng thái từ '{$currentStatus->label()}' sang '{$targetStatus->label()}'."
            );
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $currentStatus, $targetStatus, $changedBy, $note) {
            $order->update([
                'status' => $targetStatus,
            ]);

            \App\Models\OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $currentStatus->value,
                'to_status' => $targetStatus->value,
                'changed_by' => $changedBy,
                'note' => $note ?? "Cập nhật trạng thái sang {$targetStatus->label()}",
            ]);
        });

        return $order->fresh();
    }

    public function cancelOrderByCustomer(Order|int|string $order, int|string $userId, ?string $reason = null): Order
    {
        $order = $order instanceof Order ? $order : Order::findOrFail($order);

        if ($order->user_id !== (int) $userId) {
            throw new \App\Exceptions\BusinessException('Bạn không sở hữu đơn hàng này.');
        }

        if ($order->status !== OrderStatus::PENDING) {
            throw new \App\Exceptions\BusinessException('Chỉ có thể hủy đơn hàng khi đang ở trạng thái Chờ xác nhận.');
        }

        return $this->transitionTo($order, OrderStatus::CANCELLED, (int) $userId, $reason ?? 'Khách hàng tự hủy đơn');
    }
}
