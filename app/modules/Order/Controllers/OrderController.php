<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Services\OrderService;
use App\Modules\Order\Services\OrderStatusService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected OrderService $orderService,
        protected OrderStatusService $orderStatusService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = $this->orderService->listCustomerOrders($request->user()->id);

        return view('modules.Order.pages.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $order = $this->orderService->getOrderDetail($id);

        if ($order->user_id !== $request->user()->id && !$request->user()->isAdmin() && !$request->user()->isStaff()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('modules.Order.pages.show', compact('order'));
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(Request $request, string $id)
    {
        try {
            $this->orderStatusService->cancelOrderByCustomer(
                $id,
                $request->user()->id,
                $request->input('reason', 'Khách hàng yêu cầu hủy đơn')
            );

            return redirect()
                ->route('orders.show', $id)
                ->with('success', 'Đã hủy đơn hàng thành công.');
        } catch (\App\Exceptions\BusinessException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Khách hàng xác nhận đã nhận được hàng
     */
    public function received(Request $request, string $id)
    {
        $order = $this->orderService->getOrderDetail($id);

        if ($order->user_id !== $request->user()->id) {
            abort(403, 'Bạn không sở hữu đơn hàng này.');
        }

        if (!in_array($order->status, [\App\Models\Enums\OrderStatus::PENDING, \App\Models\Enums\OrderStatus::CONFIRMED, \App\Models\Enums\OrderStatus::SHIPPING], true)) {
            return redirect()
                ->back()
                ->with('error', 'Đơn hàng này không thể xác nhận nhận hàng.');
        }

        try {
            $this->orderStatusService->transitionTo(
                $order,
                \App\Models\Enums\OrderStatus::COMPLETED,
                $request->user()->id,
                'Khách hàng đã nhận được hàng và xác nhận thành công'
            );

            // Cập nhật trạng thái thanh toán thành PAID nếu là đơn COD
            if ($order->payment_status !== \App\Models\Enums\PaymentStatus::PAID) {
                $order->update(['payment_status' => \App\Models\Enums\PaymentStatus::PAID]);
            }

            return redirect()
                ->route('orders.show', $id)
                ->with('success', 'Cảm ơn bạn! Đơn hàng đã hoàn tất. Bạn có thể đánh giá sản phẩm ngay bây giờ.');
        } catch (\App\Exceptions\BusinessException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
