<?php

namespace App\Modules\Order\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Modules\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use Exception;

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
            
            if (!$cart || $cart->cartItems->isEmpty()) {
                throw new Exception("Giỏ hàng của bạn đang trống!");
            }

            $subtotal = 0;
            $itemsData = [];

            // 2. Tính tiền và chuẩn bị dữ liệu OrderItems
            foreach ($cart->cartItems as $cartItem) {
                $product = Product::lockForUpdate()->find($cartItem->product_id);

                if (!$product || $product->stock < $cartItem->quantity) {
                    throw new Exception("Sản phẩm {$product->name} không đủ số lượng trong kho!");
                }

                $subtotal += $product->price * $cartItem->quantity;

                $itemsData[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'unit_price'   => $product->price,
                    'quantity'     => $cartItem->quantity,
                ];

                // Trừ tồn kho
                $product->decrement('stock', $cartItem->quantity);
            }

            $shippingFee = 0; // Có thể gọi api tính phí ship sau

            // 3. Chuẩn bị dữ liệu lưu Order
            $orderData = [
                'code'             => 'ORD-' . strtoupper(substr(uniqid(), -8)),
                'user_id'          => $userId,
                'receiver_name'    => $data['receiver_name'],
                'phone'            => $data['phone'],
                'shipping_address' => $data['shipping_address'],
                'payment_method'   => $data['payment_method'],
                'payment_status'   => $data['payment_method'] === 'vnpay' ? 'paid' : 'unpaid',
                'subtotal'         => $subtotal,
                'shipping_fee'     => $shippingFee,
                'total'            => $subtotal + $shippingFee,
                'status'           => 'pending',
                'note'             => $data['note'] ?? null,
            ];

            // 4. Lưu vào CSDL thông qua Repository
            $order = $this->orderRepository->create($orderData, $itemsData);

            // 5. Ghi lịch sử trạng thái
            $order->statusHistories()->create([
                'to_status'  => 'pending',
                'changed_by' => $userId,
                'note'       => 'Khách hàng đặt hàng',
            ]);

            // 6. Xóa giỏ hàng sau khi đặt thành công
            $cart->cartItems()->delete();

            return $order;
        });
    }
}