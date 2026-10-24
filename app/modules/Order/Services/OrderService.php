<?php

namespace App\Modules\Order\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Modules\Cart\Services\CouponService;
use App\Modules\Order\Repositories\OrderRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository
    ) {}

    public function listCustomerOrders(int|string $userId, array $filters = [])
    {
        return $this->orderRepository->getOrdersByUserId($userId, $filters);
    }

    public function listAllOrders(array $filters = [])
    {
        return $this->orderRepository->getAll($filters);
    }

    public function getOrderDetail(int|string $id)
    {
        return $this->orderRepository->findById($id);
    }

    public function checkout(int|string $userId, array $data)
    {
        return DB::transaction(function () use ($userId, $data) {
            // 1. Lấy giỏ hàng của User từ Database (dựa theo ERD bảng carts và cart_items)
            $cart = Cart::with('cartItems.product')->where('user_id', $userId)->first();

            if (! $cart || $cart->cartItems->isEmpty()) {
                throw new Exception('Giỏ hàng của bạn đang trống!');
            }

            // Lọc các sản phẩm được chọn nếu người dùng truyền selected_ids
            $selectedIds = ! empty($data['selected_ids']) ? (array) $data['selected_ids'] : [];
            $cartItems = ! empty($selectedIds)
                ? $cart->cartItems->whereIn('id', $selectedIds)
                : $cart->cartItems;

            if ($cartItems->isEmpty()) {
                throw new Exception('Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
            }

            $subtotal = 0;
            $itemsData = [];

            // 2. Tính tiền và chuẩn bị dữ liệu OrderItems
            foreach ($cartItems as $cartItem) {
                $product = Product::lockForUpdate()->find($cartItem->product_id);

                if (! $product || $product->stock < $cartItem->quantity) {
                    $productName = $product?->name ?? 'sản phẩm';
                    throw new Exception("Sản phẩm {$productName} không đủ số lượng trong kho!");
                }

                $subtotal += $product->price * $cartItem->quantity;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $cartItem->quantity,
                ];

                // Trừ tồn kho
                $product->decrement('stock', $cartItem->quantity);
            }

            $shippingFee = 0; // Có thể gọi api tính phí ship sau
            $discount = 0;
            $couponId = null;

            // Xử lý mã giảm giá (voucher) nếu có
            if (! empty($data['coupon_code'])) {
                $couponService = app(CouponService::class);
                $couponResult = $couponService->validateAndCalculate($data['coupon_code'], $subtotal);
                if ($couponResult['valid'] && $couponResult['coupon']) {
                    $coupon = $couponResult['coupon'];
                    $discount = $couponResult['discount'];
                    $couponId = $coupon->id;
                    $coupon->increment('used_count');
                }
            }

            $total = max(0, $subtotal - $discount + $shippingFee);

            // 3. Chuẩn bị dữ liệu lưu Order
            $orderData = [
                'code' => 'ORD-'.strtoupper(substr(uniqid(), -8)),
                'user_id' => $userId,
                'coupon_id' => $couponId,
                'receiver_name' => $data['receiver_name'],
                'phone' => $data['phone'],
                'shipping_address' => $data['shipping_address'],
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_method'] === 'vnpay' ? 'paid' : 'unpaid',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'status' => 'pending',
                'note' => $data['note'] ?? null,
            ];

            // 4. Lưu vào CSDL thông qua Repository
            $order = $this->orderRepository->create($orderData, $itemsData);

            // 5. Ghi lịch sử trạng thái
            $order->statusHistories()->create([
                'to_status' => 'pending',
                'changed_by' => $userId,
                'note' => 'Khách hàng đặt hàng',
            ]);

            // 6. Xóa các sản phẩm đã đặt mua khỏi giỏ hàng
            $cart->cartItems()->whereIn('id', $cartItems->pluck('id'))->delete();

            return $order;
        });
    }
}
