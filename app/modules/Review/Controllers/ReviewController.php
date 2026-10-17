<?php

namespace App\Modules\Review\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Modules\Review\Requests\StoreReviewRequest;
use App\Modules\Review\Services\ReviewService;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    public function store(
        StoreReviewRequest $request,
        Product $product
    ): RedirectResponse {
        $this->reviewService->createReview(
            $request->user()->id,
            $product->id,
            $request->validated()
        );

        return redirect()
            ->route('products.show', $product->id)
            ->with(
                'success',
                'Cảm ơn bạn đã đánh giá sản phẩm!'
            );
    }
}