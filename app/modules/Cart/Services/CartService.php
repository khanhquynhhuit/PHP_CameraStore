<?php

namespace App\Modules\Cart\Services;

use App\Modules\Cart\Repositories\CartRepository;

class CartService
{
    public function __construct(
        protected CartRepository $cartRepository
    ) {}

    /**
     * Nơi viết các logic nghiệp vụ liên quan đến giỏ hàng
     */
    public function getCart(int|string $userId)
    {
        // TODO: Viết logic lấy giỏ hàng và tính tổng tiền
    }

    public function addToCart(int|string $userId, int|string $productId, int $quantity)
    {
        // TODO: Viết logic kiểm tra tồn kho và thêm vào giỏ
    }

    public function updateQuantity(int|string $userId, int|string $itemId, int $quantity)
    {
        // TODO: Viết logic cập nhật số lượng
    }

    public function removeItem(int|string $userId, int|string $itemId)
    {
        // TODO: Viết logic xóa mục khỏi giỏ
    }

    public function clearCart(int|string $userId)
    {
        // TODO: Viết logic dọn sạch giỏ hàng
    }
}
