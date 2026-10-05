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
        // TODO: Viết phần trả về danh sách đơn hàng cho Admin/Staff
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Viết phần trả về chi tiết đơn hàng
    }

    /**
     * Update order status.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, string $id)
    {
        // TODO: Viết phần cập nhật trạng thái đơn hàng qua OrderStatusService
    }
}
