<?php

namespace App\Controllers\Qc;

use App\Controllers\BaseController;
use App\Services\QcDashboardService;

class DashboardController extends BaseController
{
    protected QcDashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new QcDashboardService();
    }

    public function index()
    {
        return view('qc/dashboard', [
            'title'    => 'Dashboard QC',
            'heading'  => 'Dashboard QC',
            'summary'  => $this->dashboardService->getSummary(),
            'recent'   => $this->dashboardService->getRecentInspections(10),
        ]);
    }
}
