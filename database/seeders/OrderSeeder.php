<?php

namespace Database\Seeders;

use App\Models\Enums\OrderStatus;
use App\Models\Enums\PaymentMethod;
use App\Models\Enums\PaymentStatus;
use App\Models\Enums\RoleName;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = User::query()->where('role', RoleName::STAFF)->first()
            ?? User::query()->where('role', RoleName::ADMIN)->first();

        $customers = User::query()->where('role', RoleName::CUSTOMER)->with('addresses')->get();
        if ($customers->isEmpty()) {
            return;
        }

        $activeProducts = Product::query()->where('status', 'active')->where('stock', '>', 0)->get();
        if ($activeProducts->isEmpty()) {
            return;
        }

        $coupons = Coupon::all()->keyBy('code');

        // Định nghĩa 12 kịch bản đơn hàng phủ đủ 5 trạng thái
        $orderScenarios = [
            // 3 đơn PENDING
            [
                'status' => OrderStatus::PENDING,
                'days_ago' => 1,
                'payment_method' => PaymentMethod::COD,
                'payment_status' => PaymentStatus::UNPAID,
                'coupon' => null,
                'item_count' => 1,
            ],
            [
                'status' => OrderStatus::PENDING,
                'days_ago' => 2,
                'payment_method' => PaymentMethod::BANK,
                'payment_status' => PaymentStatus::UNPAID,
                'coupon' => 'WELCOME10',
                'item_count' => 2,
            ],
            [
                'status' => OrderStatus::PENDING,
                'days_ago' => 3,
                'payment_method' => PaymentMethod::VNPAY,
                'payment_status' => PaymentStatus::PAID,
                'coupon' => null,
                'item_count' => 1,
            ],

            // 2 đơn CONFIRMED
            [
                'status' => OrderStatus::CONFIRMED,
                'days_ago' => 5,
                'payment_method' => PaymentMethod::COD,
                'payment_status' => PaymentStatus::UNPAID,
                'coupon' => null,
                'item_count' => 2,
            ],
            [
                'status' => OrderStatus::CONFIRMED,
                'days_ago' => 7,
                'payment_method' => PaymentMethod::VNPAY,
                'payment_status' => PaymentStatus::PAID,
                'coupon' => 'GIAM500K',
                'item_count' => 1,
            ],

            // 2 đơn SHIPPING
            [
                'status' => OrderStatus::SHIPPING,
                'days_ago' => 10,
                'payment_method' => PaymentMethod::COD,
                'payment_status' => PaymentStatus::UNPAID,
                'coupon' => null,
                'tracking' => 'VNPOST' . fake()->numerify('#########'),
                'item_count' => 2,
            ],
            [
                'status' => OrderStatus::SHIPPING,
                'days_ago' => 14,
                'payment_method' => PaymentMethod::BANK,
                'payment_status' => PaymentStatus::PAID,
                'coupon' => 'WELCOME10',
                'tracking' => 'VTP' . fake()->numerify('#########'),
                'item_count' => 3,
            ],

            // 3 đơn COMPLETED
            [
                'status' => OrderStatus::COMPLETED,
                'days_ago' => 25,
                'payment_method' => PaymentMethod::COD,
                'payment_status' => PaymentStatus::PAID,
                'coupon' => null,
                'tracking' => 'VNPOST' . fake()->numerify('#########'),
                'item_count' => 1,
            ],
            [
                'status' => OrderStatus::COMPLETED,
                'days_ago' => 38,
                'payment_method' => PaymentMethod::VNPAY,
                'payment_status' => PaymentStatus::PAID,
                'coupon' => 'GIAM500K',
                'tracking' => 'VTP' . fake()->numerify('#########'),
                'item_count' => 2,
            ],
            [
                'status' => OrderStatus::COMPLETED,
                'days_ago' => 50,
                'payment_method' => PaymentMethod::BANK,
                'payment_status' => PaymentStatus::PAID,
                'coupon' => 'WELCOME10',
                'tracking' => 'GHN' . fake()->numerify('#########'),
                'item_count' => 2,
            ],

            // 2 đơn CANCELLED
            [
                'status' => OrderStatus::CANCELLED,
                'days_ago' => 18,
                'payment_method' => PaymentMethod::COD,
                'payment_status' => PaymentStatus::UNPAID,
                'coupon' => null,
                'cancel_reason' => 'Khách hàng đổi ý, muốn nâng cấp lên dòng máy ảnh cao hơn.',
                'cancel_from' => OrderStatus::PENDING,
                'item_count' => 1,
            ],
            [
                'status' => OrderStatus::CANCELLED,
                'days_ago' => 30,
                'payment_method' => PaymentMethod::VNPAY,
                'payment_status' => PaymentStatus::REFUNDED,
                'coupon' => null,
                'cancel_reason' => 'Khách hàng đi công tác đột xuất không thể nhận hàng, đã hoàn tiền VNPAY.',
                'cancel_from' => OrderStatus::CONFIRMED,
                'item_count' => 1,
            ],
        ];

        foreach ($orderScenarios as $index => $scenario) {
            $customer = $customers[$index % $customers->count()];
            $defaultAddress = $customer->addresses->firstWhere('is_default', true) ?? $customer->addresses->first();

            $shippingAddress = $defaultAddress
                ? "{$defaultAddress->detail}, {$defaultAddress->ward}, {$defaultAddress->district}, {$defaultAddress->province}"
                : 'Số 144 Xuân Thủy, Phường Dịch Vọng Hậu, Quận Cầu Giấy, Hà Nội';

            $createdAt = Carbon::now()->subDays($scenario['days_ago'])->setHour(rand(8, 17))->setMinute(rand(0, 59));

            // Chọn sản phẩm làm snapshot
            $selectedProducts = $activeProducts->random(min($scenario['item_count'], $activeProducts->count()));

            $subtotal = 0;
            $itemsData = [];

            foreach ($selectedProducts as $product) {
                $qty = 1;
                $unitPrice = (float) $product->price;
                $subtotal += $unitPrice * $qty;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $qty,
                ];
            }

            // Tính discount dựa trên Coupon (nếu có)
            $discount = 0;
            $appliedCoupon = null;
            if (!empty($scenario['coupon']) && isset($coupons[$scenario['coupon']])) {
                $appliedCoupon = $coupons[$scenario['coupon']];
                if ($appliedCoupon->type->value === 'percent') {
                    $discount = round(($subtotal * (float) $appliedCoupon->value) / 100);
                } else {
                    $discount = min((float) $appliedCoupon->value, $subtotal);
                }
            }

            $shippingFee = $subtotal > 20000000 ? 0 : 50000;
            $total = max(0, $subtotal - $discount + $shippingFee);

            $order = Order::query()->create([
                'code' => 'ORD' . $createdAt->format('ymd') . Str::upper(Str::random(4)),
                'user_id' => $customer->id,
                'coupon_id' => $appliedCoupon?->id,
                'receiver_name' => $customer->name,
                'phone' => $customer->phone ?? '0901122334',
                'shipping_address' => $shippingAddress,
                'payment_method' => $scenario['payment_method'],
                'payment_status' => $scenario['payment_status'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'status' => $scenario['status'],
                'cancel_reason' => $scenario['cancel_reason'] ?? null,
                'tracking_code' => $scenario['tracking'] ?? null,
                'note' => 'Đóng gói cẩn thận chống sốc, kèm phiếu bảo hành chính hãng.',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Tạo các mục chi tiết trong đơn hàng (Order items snapshot)
            foreach ($itemsData as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // Nếu đơn hàng đã hoàn thành, tạo đánh giá mẫu (Review)
                if ($scenario['status'] === OrderStatus::COMPLETED && rand(0, 1) === 1) {
                    Review::query()->firstOrCreate(
                        [
                            'user_id' => $customer->id,
                            'product_id' => $item['product_id'],
                            'order_id' => $order->id,
                        ],
                        [
                            'rating' => fake()->randomElement([4, 5]),
                            'comment' => fake()->randomElement([
                                'Máy ảnh chụp siêu nét, màu sắc tươi sáng, lấy nét nhanh như chớp!',
                                'Hàng chính hãng đóng gói rất kỹ, giao hàng nhanh, nhân viên tư vấn nhiệt tình.',
                                'Rất ưng ý với sản phẩm này, cầm đầm tay, quay phim 4K không bị nóng máy.',
                                'Chất lượng máy ảnh tuyệt vời, ống kính sắc nét, chống rung hoạt động rất tốt.',
                            ]),
                            'created_at' => (clone $createdAt)->addDays(4),
                            'updated_at' => (clone $createdAt)->addDays(4),
                        ]
                    );
                }
            }

            // Ghi nhận lịch sử trạng thái theo đúng state machine
            $this->createHistoryPath($order, $scenario, $staff->id, $createdAt);
        }
    }

    /**
     * Tạo chuỗi lịch sử trạng thái đúng theo quy tắc state machine
     */
    private function createHistoryPath(Order $order, array $scenario, int $staffId, Carbon $createdAt): void
    {
        $status = $scenario['status'];

        // Bước 1: Lúc vừa đặt đơn
        OrderStatusHistory::query()->create([
            'order_id' => $order->id,
            'from_status' => null,
            'to_status' => OrderStatus::PENDING->value,
            'changed_by' => $staffId,
            'note' => 'Đơn hàng được khởi tạo thành công bởi khách hàng.',
            'created_at' => (clone $createdAt),
        ]);

        if ($status === OrderStatus::PENDING) {
            return;
        }

        // Bước 2: Chuyển sang CONFIRMED hoặc hủy trực tiếp từ PENDING
        if ($status === OrderStatus::CANCELLED && ($scenario['cancel_from'] ?? null) === OrderStatus::PENDING) {
            OrderStatusHistory::query()->create([
                'order_id' => $order->id,
                'from_status' => OrderStatus::PENDING->value,
                'to_status' => OrderStatus::CANCELLED->value,
                'changed_by' => $staffId,
                'note' => $scenario['cancel_reason'] ?? 'Hủy đơn từ trạng thái chờ xác nhận.',
                'created_at' => (clone $createdAt)->addHours(2),
            ]);
            return;
        }

        // Đã xác nhận
        $confirmedAt = (clone $createdAt)->addHours(2);
        OrderStatusHistory::query()->create([
            'order_id' => $order->id,
            'from_status' => OrderStatus::PENDING->value,
            'to_status' => OrderStatus::CONFIRMED->value,
            'changed_by' => $staffId,
            'note' => 'Nhân viên đã kiểm tra tồn kho và xác nhận đơn hàng thành công.',
            'created_at' => $confirmedAt,
        ]);

        if ($status === OrderStatus::CONFIRMED) {
            return;
        }

        // Nếu hủy từ CONFIRMED
        if ($status === OrderStatus::CANCELLED) {
            OrderStatusHistory::query()->create([
                'order_id' => $order->id,
                'from_status' => OrderStatus::CONFIRMED->value,
                'to_status' => OrderStatus::CANCELLED->value,
                'changed_by' => $staffId,
                'note' => $scenario['cancel_reason'] ?? 'Hủy đơn sau khi đã xác nhận.',
                'created_at' => (clone $confirmedAt)->addHours(4),
            ]);
            return;
        }

        // Đang giao hàng
        $shippingAt = (clone $confirmedAt)->addDay();
        OrderStatusHistory::query()->create([
            'order_id' => $order->id,
            'from_status' => OrderStatus::CONFIRMED->value,
            'to_status' => OrderStatus::SHIPPING->value,
            'changed_by' => $staffId,
            'note' => 'Đã đóng gói và bàn giao bưu kiện cho đơn vị vận chuyển.',
            'created_at' => $shippingAt,
        ]);

        if ($status === OrderStatus::SHIPPING) {
            return;
        }

        // Đã hoàn thành
        $completedAt = (clone $shippingAt)->addDays(2);
        OrderStatusHistory::query()->create([
            'order_id' => $order->id,
            'from_status' => OrderStatus::SHIPPING->value,
            'to_status' => OrderStatus::COMPLETED->value,
            'changed_by' => $staffId,
            'note' => 'Đơn vị vận chuyển báo phát thành công, khách hàng đã nhận đủ sản phẩm.',
            'created_at' => $completedAt,
        ]);
    }
}
