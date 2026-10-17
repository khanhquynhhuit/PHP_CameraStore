<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
        // TODO: Viết phần trả về danh sách đơn hàng của khách hàng (Tâm)
        $orders = Order::where('user_id', $request->user()->id)->latest()->paginate(10);

        return view('modules.Order.pages.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        // TODO: Viết phần trả về chi tiết đơn hàng (Tâm)
        $order = Order::with(['orderItems.product.productImages', 'user'])->find($id);

        if (!$order) {
            abort(404, 'Không tìm thấy đơn hàng.');
        }

        return view('modules.Order.pages.show', compact('order'));
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(Request $request, string $id)
    {
        // TODO: Viết phần xử lý hủy đơn hàng (Tâm)
        return redirect()->back()->with('error', 'Tính năng hủy đơn hàng đang được bạn Tâm hoàn thiện!');
    }

    /**
     * Khách hàng xác nhận đã nhận được hàng
     */
    public function received(Request $request, string $id)
    {
        // TODO: Viết phần xác nhận đã nhận hàng (Tâm)
        return redirect()->back()->with('error', 'Tính năng xác nhận nhận hàng đang được bạn Tâm hoàn thiện!');
    }
}
