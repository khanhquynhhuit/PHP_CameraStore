<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Requests\CheckoutRequest;
use App\Modules\Order\Services\OrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * Display a listing of the resource (Checkout page hoặc xử lý mua ngay).
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // Nếu request gửi trực tiếp thông tin từ giỏ hàng (selected_ids, payment_method, address_id...)
        // Tiến hành tạo đơn hàng và thanh toán thành công luôn
        if ($request->has('selected_ids')) {
            $order = $this->orderService->checkout($userId, $request->all());

            return view('modules.Order.pages.success', [
                'order' => $order,
            ]);
        }

        // Mặc định hiển thị trang checkout nếu vào trực tiếp
        return view('modules.Order.pages.checkout');
    }

    /**
     * Store a newly created resource in storage (Process checkout).
     */
    public function store(Request $request)
    {
        $userId = $request->user()->id;
        $order = $this->orderService->checkout($userId, $request->all());

        if ($request->wantsJson()) {
            return $this->successResponse($order, 'Đặt hàng và thanh toán thành công!');
        }

        return view('modules.Order.pages.success', [
            'order' => $order,
        ]);
    }
}
