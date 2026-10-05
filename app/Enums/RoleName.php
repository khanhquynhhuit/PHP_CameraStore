<?php

namespace App\Enums;

enum RoleName: string
{
    case ADMIN = 'admin';
    case STAFF = 'staff';
    case CUSTOMER = 'customer';

    /**
     * Nhãn tiếng Việt cho vai trò người dùng
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Quản trị viên',
            self::STAFF => 'Nhân viên',
            self::CUSTOMER => 'Khách hàng',
        };
    }
}
