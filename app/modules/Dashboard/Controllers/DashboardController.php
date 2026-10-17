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

        if ($request->user()->isAdmin()) {
            $dashboardData =
                $this->dashboardService->getAdminDashboardData();
        }

        return view(
            'modules.Dashboard.pages.index',
            compact('dashboardData')
        );
    }
}