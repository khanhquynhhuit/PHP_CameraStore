<?php

namespace App\Modules\Order\Services;

use App\Models\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Modules\Order\Repositories\OrderRepository;
use Exception;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($order, $targetStatus, $changedBy, $note) {
            $orderModel = $order instanceof Order ? $order : $this->orderRepository->findById($order);

            $currentStatus = $orderModel->status->value ?? $orderModel->status;
            $targetValue = $targetStatus instanceof OrderStatus ? $targetStatus->value : $targetStatus;

            // Nếu trạng thái đích giống trạng thái hiện tại thì bỏ qua
            if ($currentStatus === $targetValue) {
                return $orderModel;
            }

            // Cập nhật trạng thái
            $this->orderRepository->update($orderModel, ['status' => $targetValue]);

            // Ghi lịch sử
            $orderModel->statusHistories()->create([
                'from_status' => $currentStatus,
                'to_status' => $targetValue,
                'changed_by' => $changedBy,
                'note' => $note ?? "Cập nhật trạng thái sang {$targetValue}",
            ]);

            return $orderModel;
        });
    }

    public function cancelOrderByCustomer(Order|int|string $order, int|string $userId, ?string $reason = null)
    {
        return DB::transaction(function () use ($order, $userId, $reason) {
            $orderModel = $order instanceof Order ? $order : $this->orderRepository->findById($order);
            $currentStatus = $orderModel->status->value ?? $orderModel->status;

            if ($currentStatus !== 'pending') {
                throw new Exception('Chỉ được hủy đơn hàng khi ở trạng thái Chờ duyệt.');
            }
            if ($orderModel->user_id !== $userId) {
                throw new Exception('Bạn không có quyền hủy đơn hàng này.');
            }

            // Hoàn lại tồn kho
            foreach ($orderModel->items as $item) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }

            // Cập nhật lý do hủy
            $this->orderRepository->update($orderModel, [
                'status' => 'cancelled',
                'cancel_reason' => $reason ?? 'Khách hàng tự hủy',
            ]);

            $orderModel->statusHistories()->create([
                'from_status' => $currentStatus,
                'to_status' => 'cancelled',
                'changed_by' => $userId,
                'note' => 'Khách hàng hủy đơn hàng',
            ]);

            return $orderModel;
        });
    }
}
