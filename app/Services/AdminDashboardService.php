<?php

namespace App\Services;

use App\Models\ProductUnitModel;
use App\Models\WarrantyClaimModel;
use App\Models\WarrantyModel;
use CodeIgniter\Database\BaseConnection;

class AdminDashboardService
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function getSummary(): array
    {
        return [
            'units'      => $this->getUnitSummary(),
            'warranties' => $this->getWarrantySummary(),
            'claims'     => $this->getClaimSummary(),
            'chart'      => $this->getMonthlyActivationChart(),
            'topProducts' => $this->getTopProducts(),
        ];
    }

    private function getUnitSummary(): array
    {
        $totalUnits = $this->db->table('product_units')
            ->where('deleted_at', null)
            ->countAllResults();

        $readyForSale = $this->db->table('product_units')
            ->where('status', 'Siap Dijual')
            ->where('deleted_at', null)
            ->countAllResults();

        $sold = $this->db->table('product_units')
            ->where('status', 'Terjual')
            ->where('deleted_at', null)
            ->countAllResults();

        return [
            'total'          => $totalUnits,
            'ready_for_sale' => $readyForSale,
            'sold'           => $sold,
        ];
    }

    private function getWarrantySummary(): array
    {
        $active = $this->db->table('warranties')
            ->where('status', 'Garansi Aktif')
            ->countAllResults();

        $expired = $this->db->table('warranties')
            ->where('status', 'Garansi Berakhir')
            ->countAllResults();

        $expiringSoon = $this->db->table('warranties')
            ->where('status', 'Garansi Aktif')
            ->where('end_date <=', date('Y-m-d', strtotime('+30 days')))
            ->where('end_date >=', date('Y-m-d'))
            ->countAllResults();

        return [
            'active'        => $active,
            'expired'       => $expired,
            'expiring_soon' => $expiringSoon,
        ];
    }

    private function getClaimSummary(): array
    {
        $new = $this->db->table('warranty_claims')
            ->where('status', 'Pengajuan Baru')
            ->countAllResults();

        $inProgress = $this->db->table('warranty_claims')
            ->whereIn('status', [
                'Sedang Diverifikasi',
                'Klaim Diterima',
                'Barang Diterima',
                'Sedang Diperiksa',
                'Sedang Diproses',
            ])
            ->countAllResults();

        return [
            'new'         => $new,
            'in_progress' => $inProgress,
        ];
    }

    private function getMonthlyActivationChart(): array
    {
        $sixMonthsAgo = date('Y-m-d', strtotime('-12 months'));

        $rows = $this->db->table('warranties')
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->where('created_at >=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get()
            ->getResultArray();

        $chart = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $label = date('M Y', strtotime($month . '-01'));

            $found = array_filter($rows, static fn($r) => $r['month'] === $month);
            $total = $found ? (int) array_values($found)[0]['total'] : 0;

            $chart[] = [
                'month' => $month,
                'label' => $label,
                'total' => $total,
            ];
        }
        return $chart;
    }

    private function getClaimsByStatus(): array
    {
        $rows = $this->db->table('warranty_claims')
            ->select('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        $result = [];

        foreach ($rows as $row) {
            $result[$row['status']] = (int) $row['total'];
        }

        return $result;
    }

    private function getTopProducts(int $limit = 5): array
    {
        return $this->db->table('products')
            ->select('products.id, products.name, products.brand, products.model, COUNT(sales.id) as total_sold')
            ->join('product_units', 'product_units.product_id = products.id', 'left')
            ->join('sales', 'sales.product_unit_id = product_units.id', 'left')
            ->where('products.deleted_at', null)
            ->groupBy('products.id')
            ->orderBy('total_sold', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
