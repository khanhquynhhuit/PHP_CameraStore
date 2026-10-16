<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Requests\UpdateOrderStatusRequest;
use App\Modules\Order\Services\OrderService;
use App\Modules\Order\Services\OrderStatusService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
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
        $orders = $this->orderService->listAllOrders();

        return view('modules.Order.pages.admin-index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = $this->orderService->getOrderDetail($id);
        $order->load(['statusHistories.user']);

        return view('modules.Order.pages.admin-show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, string $id)
    {
        try {
            $this->orderStatusService->transitionTo(
                $id,
                $request->validated('status'),
                $request->user()->id,
                $request->validated('note')
            );

            return redirect()
                ->route('admin.orders.show', $id)
                ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\App\Exceptions\BusinessException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
