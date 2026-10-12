<?php

namespace App\Controllers\Qc;

use App\Controllers\BaseController;
use App\Services\QcUnitService;

class QcUnitController extends BaseController
{
    protected QcUnitService $unitService;

    public function __construct()
    {
        $this->unitService = new QcUnitService();
    }

    public function index()
    {
        $units = $this->unitService->getUnitsByStatus([
            'Draft',
            'Menunggu QC',
            'Perlu Pemeriksaan Ulang',
            'Tidak Lolos QC',
        ]);

        return view('qc/units/index', [
            'title'   => 'Unit Produk',
            'heading' => 'Unit Produk',
            'units'   => $units,
        ]);
    }
}
