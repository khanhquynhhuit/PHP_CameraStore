<?php

namespace App\Modules\Cart\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Requests\AddToCartRequest;
use App\Modules\Cart\Requests\UpdateCartItemRequest;
use App\Modules\Cart\Services\CartService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO: Viết phần trả về danh sách giỏ hàng
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddToCartRequest $request)
    {
        // TODO: Viết phần thêm sản phẩm vào giỏ hàng
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartItemRequest $request, string $id)
    {
        // TODO: Viết phần cập nhật số lượng
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Viết phần xóa sản phẩm khỏi giỏ hàng
    }

    /**
     * Clear all items in cart.
     */
    public function clear()
    {
        // TODO: Viết phần làm rỗng giỏ hàng
    }
}
