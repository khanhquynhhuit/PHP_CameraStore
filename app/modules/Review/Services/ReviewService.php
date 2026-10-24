<?php

namespace App\Modules\Review\Services;

use App\Exceptions\BusinessException;
use App\Models\Review;
use App\Modules\Review\Repositories\ReviewRepository;
use Illuminate\Support\Collection;

class ReviewService
{
    public function __construct(
        protected ReviewRepository $reviewRepository
    ) {}

    public function createReview(
        int|string $userId,
        int|string $productId,
        array $data
    ): Review {
        $orderId = $data['order_id'];

        $canReview = $this->reviewRepository->canUserReviewOrder(
            $userId,
            $orderId,
            $productId
        );

        if (! $canReview) {
            throw new BusinessException(
                'Bạn chỉ được đánh giá sản phẩm đã mua trong đơn hàng hoàn tất.'
            );
        }

        $hasReviewed = $this->reviewRepository->hasReviewed(
            $userId,
            $orderId,
            $productId
        );

        if ($hasReviewed) {
            throw new BusinessException(
                'Bạn đã đánh giá sản phẩm này trong đơn hàng đã chọn.'
            );
        }

        return $this->reviewRepository->create([
            'user_id' => $userId,
            'product_id' => $productId,
            'order_id' => $orderId,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    public function getReviewableOrders(
        int|string $userId,
        int|string $productId
    ): Collection {
        return $this->reviewRepository->getReviewableOrders(
            $userId,
            $productId
        );
    }
}
