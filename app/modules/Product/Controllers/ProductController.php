<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Modules\Review\Services\ReviewService;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ProductService $productService,
        protected ReviewService $reviewService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO: Viết phần trả về danh sách sản phẩm
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $product = $this->productService->getProductDetail($id);

        $reviewableOrders = collect();
        $user = $request->user();

        if ($user && $user->isCustomer()) {
            $reviewableOrders =
                $this->reviewService->getReviewableOrders(
                    $user->id,
                    $product->id
                );
        }

        return view(
            'modules.Product.pages.show',
            compact('product', 'reviewableOrders')
        );
    }
}
