<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(Request $request): View
    {
        $dashboardData = null;

        // Báo cáo Tài chính & Doanh thu dành cho Nhân viên (Staff) vận hành cửa hàng
        if ($request->user()->isStaff()) {
            $dashboardData =
                $this->dashboardService->getAdminDashboardData();
        }

        return view(
            'modules.Dashboard.pages.index',
            compact('dashboardData')
        );
    }
}
