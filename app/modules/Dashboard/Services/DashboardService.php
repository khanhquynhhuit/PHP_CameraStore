<?php

namespace App\Modules\Dashboard\Services;

use App\Modules\Dashboard\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(
        protected DashboardRepository $dashboardRepository
    ) {}

    public function getAdminDashboardData(): array
    {
        return [
            'stats' => [
                'total_revenue' =>
                    $this->dashboardRepository->getTotalRevenue(),

                'today_revenue' =>
                    $this->dashboardRepository->getTodayRevenue(),

                'month_revenue' =>
                    $this->dashboardRepository->getCurrentMonthRevenue(),

                'completed_orders' =>
                    $this->dashboardRepository->countCompletedOrders(),

                'pending_orders' =>
                    $this->dashboardRepository->countPendingOrders(),
            ],

            'low_stock_products' =>
                $this->dashboardRepository->getLowStockProducts(),

            'best_selling_products' =>
                $this->dashboardRepository->getBestSellingProducts(),
        ];
    }
}