<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    /**
     * Người dùng sở hữu giỏ hàng
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Danh sách sản phẩm trong giỏ hàng
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Bí danh cho quan hệ danh sách sản phẩm trong giỏ hàng
     */
    public function cartItems(): HasMany
    {
        return $this->items();
    }
}
