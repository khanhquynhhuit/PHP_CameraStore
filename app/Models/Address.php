<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone',
        'province',
        'district',
        'ward',
        'detail',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    /**
     * Người dùng sở hữu địa chỉ này
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
