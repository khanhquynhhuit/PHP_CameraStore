<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleName;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Lưu ý: TUYỆT ĐỐI KHÔNG đưa 'role' vào fillable để tránh privilege escalation.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => RoleName::class,
            'is_active' => 'boolean',
        ];
    }

    /**
     * Danh sách địa chỉ giao hàng của người dùng
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Giỏ hàng của người dùng (1 - 1)
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Các đơn hàng người dùng đã đặt
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Các đánh giá sản phẩm của người dùng
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Kiểm tra vai trò admin
     */
    public function isAdmin(): bool
    {
        return $this->role === RoleName::ADMIN;
    }

    /**
     * Kiểm tra vai trò staff
     */
    public function isStaff(): bool
    {
        return $this->role === RoleName::STAFF;
    }

    /**
     * Kiểm tra vai trò customer
     */
    public function isCustomer(): bool
    {
        return $this->role === RoleName::CUSTOMER;
    }
}
