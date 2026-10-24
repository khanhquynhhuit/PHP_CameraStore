<?php

namespace App\Modules\Review\Repositories;

use App\Models\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Collection;

class ReviewRepository
{
    public function canUserReviewOrder(
        int|string $userId,
        int|string $orderId,
        int|string $productId
    ): bool {
        return Order::query()
            ->whereKey($orderId)
            ->where('user_id', $userId)
            ->where('status', OrderStatus::COMPLETED->value)
            ->whereHas(
                'orderItems',
                fn ($query) => $query->where('product_id', $productId)
            )
            ->exists();
    }

    public function hasReviewed(
        int|string $userId,
        int|string $orderId,
        int|string $productId
    ): bool {
        return Review::query()
            ->where('user_id', $userId)
            ->where('order_id', $orderId)
            ->where('product_id', $productId)
            ->exists();
    }

    public function create(array $data): Review
    {
        return Review::query()->create($data);
    }

    public function getReviewableOrders(
        int|string $userId,
        int|string $productId
    ): Collection {
        return Order::query()
            ->where('user_id', $userId)
            ->where('status', OrderStatus::COMPLETED->value)
            ->whereHas(
                'orderItems',
                fn ($query) => $query->where('product_id', $productId)
            )
            ->whereDoesntHave(
                'reviews',
                fn ($query) => $query
                    ->where('user_id', $userId)
                    ->where('product_id', $productId)
            )
            ->latest('created_at')
            ->get([
                'id',
                'code',
                'created_at',
            ]);
    }
}
