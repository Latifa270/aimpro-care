<?php

namespace App\Controllers\Qc;

use App\Controllers\BaseController;
use App\Services\QcDashboardService;

class Dashboard extends BaseController
{
    protected  QcDashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new QcDashboardService();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Qc',
            'heading' => 'Dashboars Qc',
            'summary' => $this->dashboardService->getSummary(),
        ];

        return view('qc/dashboard', $data);
    }
}
