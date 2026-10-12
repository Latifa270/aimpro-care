<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

class PublicWarrantyLookupService
{
    private BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function lookup(string $identifier): ?array
    {
        $identifier = trim($identifier);

        if ($identifier === '' || strlen($identifier) > 100) {
            return null;
        }

        $builder = $this->db->table('warranties w')
            ->select([
                'w.id AS warranty_id',
                'w.warranty_number',
                'w.start_date',
                'w.end_date',
                'w.duration_months',
                'w.status AS warranty_status',
                'w.card_path',
                's.purchase_date',
                'c.name AS customer_name',
                'p.name AS product_name',
                'p.brand AS product_brand',
                'p.model AS product_model',
                'pu.serial_number',
            ])
            ->join('sales s', 's.id = w.sales_id')
            ->join('customers c', 'c.id = s.customer_id')
            ->join('product_units pu', 'pu.id = s.product_unit_id')
            ->join('products p', 'p.id = pu.product_id');

        $builder->groupStart()
            ->where('w.qr_token', $identifier)
            ->orWhere('w.warranty_number', $identifier)
            ->orWhere('pu.serial_number', $identifier)
            ->groupEnd();

        $result = $builder->get()->getRowArray();

        if (! $result) {
            return null;
        }

        $today = date('Y-m-d');

        $result['is_expired'] = $result['end_date'] < $today;

        if ($result['is_expired']) {
            $result['display_status'] = 'Garansi Berakhir';
        } else {
            $result['display_status'] = $result['warranty_status'];
        }

        return $result;
    }
}
