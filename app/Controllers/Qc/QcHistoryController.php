<?php

namespace App\Controllers\Qc;

use App\Controllers\BaseController;
use App\Services\QcHistoryService;

class QcHistoryController extends BaseController
{
    protected QcHistoryService $historyService;

    public function __construct()
    {
        $this->historyService = new QcHistoryService();
    }

    public function index()
    {
        $inspections = $this->historyService->getByOfficer(
            (int) session()->get('user_id')
        );

        return view('qc/history/index', [
            'title'       => 'Riwayat QC',
            'heading'     => 'Riwayat QC',
            'inspections' => $inspections,
        ]);
    }
}
