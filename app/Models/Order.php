<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'coupon_id',
        'receiver_name',
        'phone',
        'shipping_address',
        'payment_method',
        'payment_status',
        'subtotal',
        'discount',
        'shipping_fee',
        'total',
        'status',
        'cancel_reason',
        'tracking_code',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'payment_method' => PaymentMethod::class,
            'payment_status' => PaymentStatus::class,
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:0',
            'discount' => 'decimal:0',
            'shipping_fee' => 'decimal:0',
            'total' => 'decimal:0',
        ];
    }

    /**
     * Người đặt đơn hàng
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mã giảm giá được áp dụng (nếu có)
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Danh sách sản phẩm chi tiết trong đơn hàng
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Bí danh cho quan hệ danh sách chi tiết đơn hàng
     */
    public function items(): HasMany
    {
        return $this->orderItems();
    }

    /**
     * Lịch sử chuyển đổi trạng thái của đơn hàng
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at');
    }

    /**
     * Đánh giá liên quan đến đơn hàng này
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
