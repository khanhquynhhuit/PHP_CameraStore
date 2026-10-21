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

    public function index(Request $request)
    {
        $orders = $this->orderService->listCustomerOrders($request->user()->id, $request->all());
        return view('modules.Order.pages.index', compact('orders'));
    }

    public function show(Request $request, string $id)
    {
        $order = $this->orderService->getOrderDetail($id);

        if ($order->user_id !== $request->user()->id) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('modules.Order.pages.show', compact('order'));
    }

    public function cancel(Request $request, string $id)
    {
        try {
            $this->orderStatusService->cancelOrderByCustomer($id, $request->user()->id, $request->input('cancel_reason'));
            return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function received(Request $request, string $id)
    {
        try {
            $order = $this->orderService->getOrderDetail($id);
            if ($order->user_id !== $request->user()->id) abort(403);

            // Chuyển sang trạng thái 'completed' (Hoàn tất)
            $this->orderStatusService->transitionTo($order, 'completed', $request->user()->id, 'Khách hàng xác nhận đã nhận hàng');
            return redirect()->back()->with('success', 'Cảm ơn bạn đã mua hàng!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}