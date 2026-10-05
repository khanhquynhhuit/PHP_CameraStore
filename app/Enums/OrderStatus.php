<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case SHIPPING = 'shipping';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    /**
     * Nhãn tiếng Việt cho trạng thái đơn hàng
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ xác nhận',
            self::CONFIRMED => 'Đã xác nhận',
            self::SHIPPING => 'Đang giao hàng',
            self::COMPLETED => 'Đã hoàn thành',
            self::CANCELLED => 'Đã hủy',
        };
    }

    /**
     * Danh sách các trạng thái hợp lệ tiếp theo (State Machine)
     *
     * @return array<self>
     */
    public function allowedNext(): array
    {
        return match ($this) {
            self::PENDING => [self::CONFIRMED, self::CANCELLED],
            self::CONFIRMED => [self::SHIPPING, self::CANCELLED],
            self::SHIPPING => [self::COMPLETED, self::CANCELLED],
            self::COMPLETED, self::CANCELLED => [],
        };
    }

    /**
     * Kiểm tra xem trạng thái đích có được phép chuyển sang từ trạng thái hiện tại không
     */
    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedNext(), true);
    }
}
