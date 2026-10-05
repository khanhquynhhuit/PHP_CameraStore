<?php

namespace App\Modules\Cart\Repositories;

use App\Models\Cart;
use App\Models\CartItem;

class CartRepository
{
    /**
     * Nơi viết các truy vấn Database liên quan đến giỏ hàng
     */
    public function getCartByUserId(int|string $userId)
    {
        // TODO: Viết query lấy giỏ hàng của user
    }

    public function findCartItem(int|string $cartId, int|string $productId)
    {
        // TODO: Viết query tìm sản phẩm trong giỏ
    }

    public function addItem(int|string $cartId, int|string $productId, int $quantity)
    {
        // TODO: Viết query thêm sản phẩm vào giỏ
    }

    public function updateItem(CartItem|int|string $item, int $quantity)
    {
        // TODO: Viết query cập nhật số lượng
    }

    public function removeItem(CartItem|int|string $item)
    {
        // TODO: Viết query xóa sản phẩm khỏi giỏ
    }

    public function clearCart(int|string $cartId)
    {
        // TODO: Viết query làm rỗng giỏ hàng
    }
}
