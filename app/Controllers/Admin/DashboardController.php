<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AdminDashboardService;

class DashboardController extends BaseController
{
    protected AdminDashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new AdminDashboardService();
    }

    public function index()
    {
        return view(
            'admin/dashboard',
            [
                'title'   => 'Dashboard',
                'heading' => 'Dashboard Admin',
                'summary' => $this->dashboardService->getSummary(),
            ]
        );
    }
}
