<?php

namespace App\Models\Enums;

enum ProductStatus: string
{
    case ACTIVE = 'active';
    case HIDDEN = 'hidden';

    /**
     * Nhãn tiếng Việt cho trạng thái sản phẩm
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Đang kinh doanh',
            self::HIDDEN => 'Ẩn khỏi cửa hàng',
        };
    }
}
