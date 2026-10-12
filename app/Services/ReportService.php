<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

class ReportService
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function getReport(string $startDate, string $endDate): array
    {
        return [
            'units'             => $this->getUnitSummary(),
            'warranties'        => $this->getWarrantySummary(),
            'claims'            => $this->getClaimSummary(),
            'qc'                => $this->getQcSummary($startDate, $endDate),
            'claims_by_status'  => $this->getClaimsByStatus(),
        ];
    }

    private function getUnitSummary(): array
    {
        return [
            'total'          => $this->db->table('product_units')
                ->where('deleted_at', null)
                ->countAllResults(),
            'ready_for_sale' => $this->db->table('product_units')
                ->where('status', 'Siap Dijual')
                ->where('deleted_at', null)
                ->countAllResults(),
            'sold'           => $this->db->table('product_units')
                ->where('status', 'Terjual')
                ->where('deleted_at', null)
                ->countAllResults(),
        ];
    }

    private function getWarrantySummary(): array
    {
        return [
            'active'  => $this->db->table('warranties')
                ->where('status', 'Garansi Aktif')
                ->countAllResults(),
            'expired' => $this->db->table('warranties')
                ->where('status', 'Garansi Berakhir')
                ->countAllResults(),
        ];
    }

    private function getClaimSummary(): array
    {
        return [
            'total'     => $this->db->table('warranty_claims')
                ->countAllResults(),
            'completed' => $this->db->table('warranty_claims')
                ->where('status', 'Selesai')
                ->countAllResults(),
        ];
    }

    private function getQcSummary(string $startDate, string $endDate): array
    {
        $start = $startDate . ' 00:00:00';
        $end   = $endDate . ' 23:59:59';

        return [
            'passed'       => $this->db->table('qc_inspections')
                ->where('result', 'Lolos QC')
                ->where('inspection_date >=', $start)
                ->where('inspection_date <=', $end)
                ->countAllResults(),
            'failed'       => $this->db->table('qc_inspections')
                ->where('result', 'Tidak Lolos QC')
                ->where('inspection_date >=', $start)
                ->where('inspection_date <=', $end)
                ->countAllResults(),
            'reinspection' => $this->db->table('qc_inspections')
                ->where('result', 'Perlu Pemeriksaan Ulang')
                ->where('inspection_date >=', $start)
                ->where('inspection_date <=', $end)
                ->countAllResults(),
        ];
    }

    private function getClaimsByStatus(): array
    {
        $rows = $this->db->table('warranty_claims')
            ->select('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $result = [];

        foreach ($rows as $row) {
            $result[$row['status']] = (int) $row['total'];
        }

        return $result;
    }
}
