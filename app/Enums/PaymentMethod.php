<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case COD = 'cod';
    case BANK = 'bank';
    case VNPAY = 'vnpay';

    /**
     * Nhãn tiếng Việt cho phương thức thanh toán
     */
    public function label(): string
    {
        return match ($this) {
            self::COD => 'Thanh toán khi nhận hàng (COD)',
            self::BANK => 'Chuyển khoản ngân hàng',
            self::VNPAY => 'Ví điện tử VNPAY',
        };
    }
}
