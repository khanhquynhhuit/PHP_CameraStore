<?php

namespace App\Modules\Cart\Repositories;

use App\Models\Cart;
use App\Models\CartItem;

class CartRepository
{
    /**
     * Lấy hoặc tạo mới giỏ hàng theo User ID kèm eager loading items và product
     */
    public function getCartByUserId(int|string $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId])
            ->load(['items.product.productImages']);
    }

    /**
     * Tìm sản phẩm cụ thể trong giỏ hàng
     */
    public function findCartItem(int|string $cartId, int|string $productId): ?CartItem
    {
        return CartItem::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * Tìm CartItem theo ID thuộc về Cart cụ thể
     */
    public function findItemByIdAndCartId(int|string $itemId, int|string $cartId): ?CartItem
    {
        return CartItem::where('id', $itemId)
            ->where('cart_id', $cartId)
            ->first();
    }

    /**
     * Thêm mới sản phẩm vào giỏ hàng
     */
    public function addItem(int|string $cartId, int|string $productId, int $quantity): CartItem
    {
        return CartItem::create([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public function updateItem(CartItem|int|string $item, int $quantity): bool
    {
        if (!$item instanceof CartItem) {
            $item = CartItem::findOrFail($item);
        }

        return $item->update(['quantity' => $quantity]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeItem(CartItem|int|string $item): bool
    {
        if (!$item instanceof CartItem) {
            $item = CartItem::findOrFail($item);
        }

        return (bool) $item->delete();
    }

    /**
     * Làm rỗng toàn bộ giỏ hàng
     */
    public function clearCart(int|string $cartId): int
    {
        return CartItem::where('cart_id', $cartId)->delete();
    }
}
