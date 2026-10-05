<?php

namespace Database\Seeders;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => CouponType::PERCENT,
                'value' => 10,
                'min_order' => 500000,
                'max_uses' => 100,
                'used_count' => 5,
                'expires_at' => now()->addDays(90),
                'is_active' => true,
            ],
            [
                'code' => 'GIAM500K',
                'type' => CouponType::FIXED,
                'value' => 500000,
                'min_order' => 10000000,
                'max_uses' => 50,
                'used_count' => 12,
                'expires_at' => now()->addDays(60),
                'is_active' => true,
            ],
            [
                'code' => 'EXPIRED5',
                'type' => CouponType::PERCENT,
                'value' => 5,
                'min_order' => 1000000,
                'max_uses' => 20,
                'used_count' => 20,
                'expires_at' => now()->subDays(15), // Đã hết hạn để test lỗi
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::query()->firstOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}
