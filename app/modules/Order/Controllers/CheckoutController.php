<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Modules\Order\Requests\CheckoutRequest;
use App\Modules\Order\Services\OrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        $cart = Cart::with('cartItems.product')->where('user_id', $user->id)->first();
        if (! $cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $selectedAddressId = $request->query('address_id');
        $address = null;

        if ($selectedAddressId) {
            $address = $user->addresses()->where('id', $selectedAddressId)->first();
        }

        if (! $address) {
            $address = $user->addresses()->where('is_default', true)->first()
                ?? $user->addresses()->first();
        }

        $formattedAddress = $address
            ? trim("{$address->detail}, {$address->ward}, {$address->district}, {$address->province}", ', ')
            : '';

        $receiverName = $address?->receiver_name ?? $user->name;
        $phone = $address?->phone ?? $user->phone;
        $paymentMethod = $request->query('payment_method', 'cod');

        $coupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->get();

        return view('modules.Order.pages.checkout', compact(
            'address',
            'formattedAddress',
            'receiverName',
            'phone',
            'paymentMethod',
            'coupons'
        ));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            if (empty($data['receiver_name'])) {
                $data['receiver_name'] = $request->user()->name;
            }

            $order = $this->orderService->checkout($request->user()->id, $data);

            // Đặt hàng xong chuyển tới trang chi tiết đơn hàng
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
