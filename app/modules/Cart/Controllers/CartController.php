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
     * Hiển thị danh sách sản phẩm trong giỏ hàng
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $cartData = $this->cartService->getCart($userId);

        if ($request->wantsJson()) {
            return $this->successResponse($cartData, 'Lấy thông tin giỏ hàng thành công');
        }

        $addresses = \App\Models\Address::where('user_id', $userId)->get();

        return view('modules.Cart.pages.index', [
            'cart' => $cartData['cart'],
            'items' => $cartData['items'],
            'subtotal' => $cartData['subtotal'],
            'totalItems' => $cartData['total_items'],
            'total' => $cartData['total'],
            'addresses' => $addresses,
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function store(AddToCartRequest $request)
    {
        $userId = $request->user()->id;
        $productId = $request->validated('product_id');
        $quantity = (int) $request->validated('quantity', 1);

        $this->cartService->addToCart($userId, $productId, $quantity);

        if ($request->wantsJson()) {
            return $this->successResponse(null, 'Đã thêm sản phẩm vào giỏ hàng');
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng thành công!');
    }

    /**
     * Cập nhật số lượng sản phẩm
     */
    public function update(UpdateCartItemRequest $request, string $id)
    {
        $userId = $request->user()->id;
        $quantity = (int) $request->validated('quantity');

        $this->cartService->updateQuantity($userId, $id, $quantity);

        if ($request->wantsJson()) {
            return $this->successResponse(null, 'Cập nhật số lượng thành công');
        }

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật số lượng sản phẩm!');
    }

    /**
     * Xóa 1 sản phẩm khỏi giỏ hàng
     */
    public function destroy(Request $request, string $id)
    {
        $userId = $request->user()->id;
        $this->cartService->removeItem($userId, $id);

        if ($request->wantsJson()) {
            return $this->successResponse(null, 'Đã xóa sản phẩm khỏi giỏ hàng');
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    /**
     * Làm rỗng toàn bộ giỏ hàng
     */
    public function clear(Request $request)
    {
        $userId = $request->user()->id;
        $this->cartService->clearCart($userId);

        if ($request->wantsJson()) {
            return $this->successResponse(null, 'Đã làm rỗng giỏ hàng');
        }

        return redirect()->route('cart.index')->with('success', 'Đã làm rỗng giỏ hàng thành công!');
    }
}
