<?php

namespace App\Services;

use App\Models\ProductUnitModel;
use App\Models\QcInspectionModel;

class QcDashboardService
{
    protected ProductUnitModel $productUnitModel;
    protected QcInspectionModel $qcInspectionModel;

    public function __construct()
    {
        $this->productUnitModel = new ProductUnitModel();
        $this->qcInspectionModel = new QcInspectionModel();
    }

    public function getSummary(): array
    {
        $today = date('Y-m-d');

        $waitingQc = $this->productUnitModel
            ->where('status', 'Menunggu QC')
            ->countAllResults();

        $reinspection = $this->productUnitModel
            ->where('status', 'Perlu Pemeriksaan Ulang')
            ->countAllResults();

        $inspectedToday = $this->qcInspectionModel
            ->where('inspection_date >=', $today . ' 00:00:00')
            ->where('inspection_date <=', $today . ' 23:59:59')
            ->countAllResults();

        $passed = $this->qcInspectionModel
            ->where('result', 'Lolos QC')
            ->countAllResults();

        $failed = $this->qcInspectionModel
            ->where('result', 'Tidak Lolos QC')
            ->countAllResults();

        return [
            'waiting_qc'      => $waitingQc,
            'inspected_today' => $inspectedToday,
            'passed'          => $passed,
            'failed'          => $failed,
            'reinspection'    => $reinspection,
        ];
    }
}
