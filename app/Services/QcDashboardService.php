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
        $userId = (int) session()->get('user_id');

        $waitingQc = $this->productUnitModel
            ->where('status', 'Menunggu QC')
            ->countAllResults();

        $reinspection = $this->productUnitModel
            ->where('status', 'Perlu Pemeriksaan Ulang')
            ->countAllResults();

        $inspectedToday = $this->qcInspectionModel
            ->where('user_id', $userId)
            ->where('inspection_date >=', $today . ' 00:00:00')
            ->where('inspection_date <=', $today . ' 23:59:59')
            ->countAllResults();

        $passed = $this->qcInspectionModel
            ->where('user_id', $userId)
            ->where('result', 'Lolos QC')
            ->countAllResults();

        $failed = $this->qcInspectionModel
            ->where('user_id', $userId)
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

    public function getRecentInspections(int $limit = 10): array
    {
        $userId = (int) session()->get('user_id');

        return $this->qcInspectionModel
            ->select('
                qc_inspections.*,
                product_units.serial_number,
                products.name AS product_name,
                products.brand,
                products.model
            ')
            ->join(
                'product_units',
                'product_units.id = qc_inspections.product_unit_id'
            )
            ->join(
                'products',
                'products.id = product_units.product_id'
            )
            ->where('qc_inspections.user_id', $userId)
            ->orderBy('qc_inspections.inspection_date', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
