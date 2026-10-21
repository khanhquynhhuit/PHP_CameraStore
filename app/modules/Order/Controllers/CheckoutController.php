<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest; // Thêm Form Request để validate
use App\Modules\Order\Services\OrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index(Request $request)
    {
        return view('modules.Order.pages.checkout');
    }

    public function store(CheckoutRequest $request) // Dùng CheckoutRequest bắt lỗi Tiếng Việt
    {
        try {
            $order = $this->orderService->checkout($request->user()->id, $request->validated());
            
            // Đặt hàng xong chuyển tới trang chi tiết đơn hàng
            return redirect()->route('orders.show', $order->id)
                             ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}