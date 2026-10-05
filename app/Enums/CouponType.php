<?php

namespace App\Enums;

enum CouponType: string
{
    case PERCENT = 'percent';
    case FIXED = 'fixed';

    /**
     * Nhãn tiếng Việt cho loại mã giảm giá
     */
    public function label(): string
    {
        return match ($this) {
            self::PERCENT => 'Giảm theo phần trăm (%)',
            self::FIXED => 'Giảm số tiền cố định (VNĐ)',
        };
    }
}
