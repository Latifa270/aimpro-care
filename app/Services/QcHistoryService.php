<?php

namespace App\Services;

use App\Models\QcInspectionModel;

class QcHistoryService
{
    protected QcInspectionModel $inspectionModel;

    public function __construct()
    {
        $this->inspectionModel = new QcInspectionModel();
    }

    public function getByOfficer(int $userId): array
    {
        return $this->inspectionModel
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
            ->findAll();
    }
}
