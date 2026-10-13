<?php

namespace App\Modules\Order\Services;

use App\Exceptions\BusinessException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\User;
use App\Models\Address;
use App\Models\Enums\OrderStatus;
use App\Models\Enums\PaymentMethod;
use App\Models\Enums\PaymentStatus;
use App\Modules\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository
    ) {}

    public function listCustomerOrders(int|string $userId, array $filters = [])
    {
        return Order::with(['orderItems.product.productImages'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);
    }

    public function listAllOrders(array $filters = [])
    {
        return Order::with(['user', 'orderItems.product'])
            ->latest()
            ->paginate(15);
    }

    public function getOrderDetail(int|string $id)
    {
        $order = Order::with(['orderItems.product.productImages', 'user'])->find($id);
        if (!$order) {
            throw new ResourceNotFoundException('Không tìm thấy đơn hàng yêu cầu.');
        }

        return $order;
    }

    /**
     * Logic nghiệp vụ đặt hàng (Giả lập thanh toán thành công cho cả COD và Chuyển khoản)
     */
    public function checkout(int|string $userId, array $data): Order
    {
        return DB::transaction(function () use ($userId, $data) {
            $user = User::findOrFail($userId);
            
            // Lấy danh sách item được chọn
            $selectedItemIds = !empty($data['selected_ids']) 
                ? (is_array($data['selected_ids']) ? $data['selected_ids'] : explode(',', $data['selected_ids'])) 
                : [];

            $cart = Cart::where('user_id', $userId)->first();
            if (!$cart) {
                throw new BusinessException('Giỏ hàng của bạn đang trống!');
            }

            $query = CartItem::with('product')->where('cart_id', $cart->id);
            if (!empty($selectedItemIds)) {
                $query->whereIn('id', $selectedItemIds);
            }
            $cartItems = $query->get();

            if ($cartItems->isEmpty()) {
                throw new BusinessException('Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
            }

            // Tính tiền & Kiểm tra tồn kho
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $product = $item->product;
                if ($item->quantity > $product->stock) {
                    throw new BusinessException("Sản phẩm '{$product->name}' trong kho không đủ (chỉ còn {$product->stock} máy)!");
                }
                $subtotal += ((float) $product->price * $item->quantity);
            }

            $vat = round($subtotal * 0.10); // VAT 10%
            $shippingFee = 0; // Freeship
            $discount = 0;
            $total = $subtotal + $vat + $shippingFee - $discount;

            // Lấy thông tin người nhận & địa chỉ
            $receiverName = $data['receiver_name'] ?? $user->name;
            $phone = $data['phone'] ?? $user->phone ?? '0901234567';
            $shippingAddress = $data['shipping_address'] ?? '';

            if (empty($shippingAddress) && !empty($data['address_id'])) {
                $addr = Address::where('user_id', $userId)->find($data['address_id']);
                if ($addr) {
                    $receiverName = $addr->receiver_name;
                    $phone = $addr->phone;
                    $shippingAddress = "{$addr->detail}, {$addr->ward}, {$addr->district}, {$addr->province}";
                }
            }

            if (empty($shippingAddress)) {
                $defaultAddr = Address::where('user_id', $userId)->first();
                $shippingAddress = $defaultAddr 
                    ? "{$defaultAddr->detail}, {$defaultAddr->ward}, {$defaultAddr->district}, {$defaultAddr->province}"
                    : 'Showroom Kyma.vn, Cầu Giấy, Hà Nội';
            }

            $paymentMethod = $data['payment_method'] ?? 'cod';
            if ($paymentMethod === 'bank') {
                $paymentMethodEnum = PaymentMethod::BANK;
            } elseif ($paymentMethod === 'vnpay') {
                $paymentMethodEnum = PaymentMethod::VNPAY;
            } else {
                $paymentMethodEnum = PaymentMethod::COD;
            }

            // Sinh mã đơn hàng ORD-XXXXXX
            $orderCode = 'ORD-' . strtoupper(Str::random(8));

            // Tạo bản ghi Order: Theo yêu cầu của bạn, cả 2 phương thức đều thành công, thanh toán = paid, đơn = confirmed
            $order = Order::create([
                'code' => $orderCode,
                'user_id' => $userId,
                'receiver_name' => $receiverName,
                'phone' => $phone,
                'shipping_address' => $shippingAddress,
                'payment_method' => $paymentMethodEnum,
                'payment_status' => PaymentStatus::PAID, // Ghi nhận thanh toán thành công
                'subtotal' => $subtotal + $vat,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'status' => OrderStatus::CONFIRMED, // Tự động xác nhận đơn
                'note' => $data['note'] ?? 'Đơn hàng đặt trực tiếp qua web',
            ]);

            // Tạo các OrderItems & Trừ tồn kho máy ảnh
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total' => (float) $item->product->price * $item->quantity,
                ]);

                // Trừ tồn kho máy ảnh
                $item->product->decrement('stock', $item->quantity);
            }

            // Xóa các sản phẩm đã mua khỏi giỏ hàng
            CartItem::whereIn('id', $cartItems->pluck('id'))->delete();

            // Ghi lịch sử trạng thái đơn hàng
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => OrderStatus::CONFIRMED,
                'changed_by' => $userId,
                'note' => 'Đơn hàng thanh toán thành công qua ' . $paymentMethodEnum->label(),
            ]);

            return $order;
        });
    }
}
