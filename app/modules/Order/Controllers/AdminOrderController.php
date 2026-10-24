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

    public function index(Request $request)
    {
        $orders = $this->orderService->listAllOrders($request->all());

        return view('modules.Order.pages.admin-index', compact('orders'));
    }

    public function show(string $id)
    {
        $order = $this->orderService->getOrderDetail($id);

        return view('modules.Order.pages.admin-show', compact('order'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, string $id)
    {
        try {
            $this->orderStatusService->transitionTo(
                $id,
                $request->input('status'),
                $request->user()->id, // ID của Admin/Staff đang thao tác
                $request->input('note')
            );

            return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
