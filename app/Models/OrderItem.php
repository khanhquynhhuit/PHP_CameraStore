<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'unit_price',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:0',
            'quantity' => 'integer',
        ];
    }

    /**
     * Đơn hàng chứa sản phẩm này
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Sản phẩm tương ứng (hỗ trợ cả sản phẩm đã bị soft-delete)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
