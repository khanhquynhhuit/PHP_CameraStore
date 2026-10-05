<?php

namespace App\Models;

use App\Models\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'sku',
        'name',
        'slug',
        'price',
        'original_price',
        'stock',
        'description',
        'specs',
        'warranty_months',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:0',
            'original_price' => 'decimal:0',
            'stock' => 'integer',
            'specs' => 'array',
            'warranty_months' => 'integer',
            'status' => ProductStatus::class,
        ];
    }

    /**
     * Danh mục của sản phẩm
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Thương hiệu của sản phẩm
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Người tạo sản phẩm (staff / admin)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Toàn bộ danh sách hình ảnh sản phẩm
     */
    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Bí danh cho quan hệ danh sách hình ảnh
     */
    public function images(): HasMany
    {
        return $this->productImages();
    }

    /**
     * Quan hệ lấy ảnh đại diện chính (is_primary = true)
     */
    public function primaryImageRelation(): HasOne
    {
        return $this->hasOne(ProductImage::class)
            ->ofMany(['sort_order' => 'asc', 'id' => 'asc'], fn ($query) => $query->where('is_primary', true));
    }

    /**
     * Accessor primaryImage: Trả về ảnh đại diện chính hoặc ảnh đầu tiên nếu có
     */
    protected function primaryImage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->relationLoaded('productImages')) {
                    return $this->productImages->firstWhere('is_primary', true) ?? $this->productImages->first();
                }
                return $this->primaryImageRelation()->first() ?? $this->productImages()->first();
            }
        );
    }

    /**
     * Các mục trong đơn hàng chứa sản phẩm này
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Đánh giá của khách hàng về sản phẩm
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
