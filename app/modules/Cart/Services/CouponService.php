<?php

namespace App\Modules\Cart\Services;

use App\Models\Coupon;
use App\Models\Enums\CouponType;

class CouponService
{
    /**
     * Kiểm tra và tính toán giảm giá của mã voucher dựa trên tổng tiền tạm tính.
     *
     * @return array{
     *     valid: bool,
     *     message: string,
     *     coupon: ?Coupon,
     *     discount: float
     * }
     */
    public function validateAndCalculate(string $code, float $subtotal): array
    {
        $code = trim(strtoupper($code));

        if ($code === '') {
            return [
                'valid' => false,
                'message' => 'Vui lòng nhập mã giảm giá.',
                'coupon' => null,
                'discount' => 0,
            ];
        }

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return [
                'valid' => false,
                'message' => "Mã giảm giá '{$code}' không tồn tại trong hệ thống.",
                'coupon' => null,
                'discount' => 0,
            ];
        }

        if (! $coupon->is_active) {
            return [
                'valid' => false,
                'message' => "Mã giảm giá '{$code}' hiện đang bị tạm khóa.",
                'coupon' => null,
                'discount' => 0,
            ];
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return [
                'valid' => false,
                'message' => "Mã giảm giá '{$code}' đã hết hạn sử dụng.",
                'coupon' => null,
                'discount' => 0,
            ];
        }

        if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
            return [
                'valid' => false,
                'message' => "Mã giảm giá '{$code}' đã hết lượt sử dụng.",
                'coupon' => null,
                'discount' => 0,
            ];
        }

        if ($coupon->min_order !== null && $subtotal < (float) $coupon->min_order) {
            $formattedMin = number_format((float) $coupon->min_order, 0, ',', '.').' đ';

            return [
                'valid' => false,
                'message' => "Đơn hàng tối thiểu phải từ {$formattedMin} để áp dụng mã này.",
                'coupon' => null,
                'discount' => 0,
            ];
        }

        // Tính số tiền giảm giá
        $discount = 0.0;
        if ($coupon->type === CouponType::PERCENT) {
            $discount = round(($subtotal * (float) $coupon->value) / 100);
        } else {
            $discount = min((float) $coupon->value, $subtotal);
        }

        $message = $coupon->type === CouponType::PERCENT
            ? "Áp dụng mã '{$coupon->code}' thành công! Giảm {$coupon->value}%"
            : "Áp dụng mã '{$coupon->code}' thành công! Giảm ".number_format($discount, 0, ',', '.').' đ';

        return [
            'valid' => true,
            'message' => $message,
            'coupon' => $coupon,
            'discount' => (float) $discount,
        ];
    }
}
