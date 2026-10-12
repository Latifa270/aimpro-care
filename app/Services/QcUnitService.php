<?php

namespace App\Services;

use App\Models\ProductUnitModel;

class QcUnitService
{
    protected ProductUnitModel $productUnitModel;

    public function __construct()
    {
        $this->productUnitModel = new ProductUnitModel();
    }

    public function getUnitsByStatus(array $statuses): array
    {
        return $this->productUnitModel
            ->select('
                product_units.*,
                products.name AS product_name,
                products.brand,
                products.model
            ')
            ->join(
                'products',
                'products.id = product_units.product_id'
            )
            ->whereIn('product_units.status', $statuses)
            ->orderBy('product_units.created_at', 'DESC')
            ->findAll();
    }
}
