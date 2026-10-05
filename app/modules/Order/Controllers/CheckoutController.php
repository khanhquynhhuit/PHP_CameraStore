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
     * Display a listing of the resource (Checkout page).
     */
    public function index(Request $request)
    {
        // TODO: Viết phần trả về thông tin checkout
    }

    /**
     * Store a newly created resource in storage (Process checkout).
     */
    public function store(CheckoutRequest $request)
    {
        // TODO: Viết phần xử lý thanh toán / tạo đơn hàng
    }
}
