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
        $orders = \App\Models\Order::with(['user', 'orderItems.product'])->latest()->paginate(15);
        return view('modules.Order.pages.admin-index', compact('orders'));
    }

    public function show(string $id)
    {
        $order = \App\Models\Order::with(['orderItems.product.productImages', 'user', 'statusHistories.user'])->findOrFail($id);
        return view('modules.Order.pages.admin-show', compact('order'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, string $id)
    {
        // TODO: Viết phần cập nhật trạng thái đơn hàng qua OrderStatusService (Tâm)
        return redirect()->back()->with('error', 'Tính năng cập nhật trạng thái đơn đang được hoàn thiện!');
    }
}
