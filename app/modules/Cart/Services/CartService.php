<?php

namespace App\Modules\Cart\Services;

use App\Exceptions\BusinessException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Cart;
use App\Models\Product;
use App\Modules\Cart\Repositories\CartRepository;

class CartService
{
    public function __construct(
        protected CartRepository $cartRepository
    ) {}

    /**
     * Lấy giỏ hàng của user và tính toán tổng tiền
     */
    public function getCart(int|string $userId): array
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $items = $cart->items;

        $subtotal = 0;
        $totalItems = 0;

        foreach ($items as $item) {
            $price = (float) $item->product->price;
            $lineTotal = $price * $item->quantity;
            $item->line_total = $lineTotal;
            $subtotal += $lineTotal;
            $totalItems += $item->quantity;
        }

        return [
            'cart' => $cart,
            'items' => $items,
            'subtotal' => $subtotal,
            'total_items' => $totalItems,
            'total' => $subtotal, // Miễn phí vận chuyển mặc định
        ];
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (Kiểm tra tồn kho & ném BusinessException nếu thiếu hàng)
     */
    public function addToCart(int|string $userId, int|string $productId, int $quantity = 1): void
    {
        $product = Product::find($productId);
        if (!$product) {
            throw new ResourceNotFoundException('Không tìm thấy sản phẩm yêu cầu.');
        }

        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItem = $this->cartRepository->findCartItem($cart->id, $productId);

        $newQuantity = $cartItem ? ($cartItem->quantity + $quantity) : $quantity;

        // Quy tắc: Nếu vượt quá tồn kho, throw new BusinessException
        if ($newQuantity > $product->stock) {
            throw new BusinessException('Số lượng sản phẩm trong kho không đủ! (Chỉ còn ' . $product->stock . ' sản phẩm)');
        }

        if ($cartItem) {
            $this->cartRepository->updateItem($cartItem, $newQuantity);
        } else {
            $this->cartRepository->addItem($cart->id, $productId, $newQuantity);
        }
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public function updateQuantity(int|string $userId, int|string $itemId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($userId, $itemId);
            return;
        }

        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItem = $this->cartRepository->findItemByIdAndCartId($itemId, $cart->id);

        if (!$cartItem) {
            throw new ResourceNotFoundException('Sản phẩm không có trong giỏ hàng.');
        }

        $product = $cartItem->product;
        if ($quantity > $product->stock) {
            throw new BusinessException('Số lượng sản phẩm trong kho không đủ! (Chỉ còn ' . $product->stock . ' sản phẩm)');
        }

        $this->cartRepository->updateItem($cartItem, $quantity);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeItem(int|string $userId, int|string $itemId): void
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItem = $this->cartRepository->findItemByIdAndCartId($itemId, $cart->id);

        if (!$cartItem) {
            throw new ResourceNotFoundException('Sản phẩm không tồn tại trong giỏ hàng.');
        }

        $this->cartRepository->removeItem($cartItem);
    }

    /**
     * Xóa sạch toàn bộ giỏ hàng
     */
    public function clearCart(int|string $userId): void
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $this->cartRepository->clearCart($cart->id);
    }
}
