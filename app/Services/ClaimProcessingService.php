<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use DomainException;
use RuntimeException;

class ClaimProcessingService
{
    private BaseConnection $db;

    private const STATUSES = [
        'Pengajuan Baru',
        'Sedang Diverifikasi',
        'Klaim Diterima',
        'Klaim Ditolak',
        'Barang Diterima',
        'Sedang Diperiksa',
        'Sedang Diproses',
        'Selesai',
    ];

    private const TRANSITIONS = [
        'Pengajuan Baru' => [
            'Sedang Diverifikasi',
        ],
        'Sedang Diverifikasi' => [
            'Klaim Diterima',
            'Klaim Ditolak',
        ],
        'Klaim Diterima' => [
            'Barang Diterima',
        ],
        'Barang Diterima' => [
            'Sedang Diperiksa',
        ],
        'Sedang Diperiksa' => [
            'Sedang Diproses',
        ],
        'Sedang Diproses' => [
            'Selesai',
        ],
        'Klaim Ditolak' => [],
        'Selesai' => [],
    ];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function changeStatus(
        int $claimId,
        int $adminId,
        string $newStatus,
        ?string $note = null
    ): array {
        if (! in_array($newStatus, self::STATUSES, true)) {
            throw new DomainException('Status klaim tidak valid.');
        }

        $note = $note !== null ? trim($note) : null;
        $note = $note === '' ? null : $note;

        $this->db->transBegin();

        try {
            $claim = $this->db->query(
                'SELECT id, status
                 FROM warranty_claims
                 WHERE id = ?
                 FOR UPDATE',
                [$claimId]
            )->getRowArray();

            if (! $claim) {
                throw new DomainException('Data klaim tidak ditemukan.');
            }

            $currentStatus = $claim['status'];

            if (! isset(self::TRANSITIONS[$currentStatus])) {
                throw new DomainException(
                    'Status klaim saat ini tidak dikenali.'
                );
            }

            if (! in_array(
                $newStatus,
                self::TRANSITIONS[$currentStatus],
                true
            )) {
                throw new DomainException(
                    "Perubahan status dari {$currentStatus} ke {$newStatus} tidak diizinkan."
                );
            }

            $now = date('Y-m-d H:i:s');

            // Perbarui status klaim.
            $updated = $this->db->table('warranty_claims')
                ->where('id', $claimId)
                ->update([
                    'status'     => $newStatus,
                    'updated_at' => $now,
                ]);

            if (! $updated) {
                throw new RuntimeException(
                    'Gagal memperbarui status klaim.'
                );
            }

            $historyInserted = $this->db->table('claim_histories')->insert([
                'warranty_claim_id' => $claimId,
                'user_id'           => $adminId,
                'status'            => $newStatus,
                'note'              => $note,
                'created_at'        => $now,
            ]);

            if (! $historyInserted) {
                throw new RuntimeException(
                    'Gagal menyimpan riwayat perubahan status.'
                );
            }

            if ($this->db->transStatus() === false) {
                throw new RuntimeException(
                    'Transaksi perubahan status klaim gagal.'
                );
            }

            $this->db->transCommit();

            $this->notifyClaimStatus(
                $claimId,
                $newStatus,
                $note
            );

            return [
                'claim_id'      => $claimId,
                'previous_status' => $currentStatus,
                'current_status'  => $newStatus,
            ];
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function getHistory(int $claimId): array
    {
        return $this->db->table('claim_histories ch')
            ->select(
                'ch.id,
                 ch.warranty_claim_id,
                 ch.user_id,
                 ch.status,
                 ch.note,
                 ch.created_at,
                 u.name AS admin_name'
            )
            ->join('users u', 'u.id = ch.user_id', 'left')
            ->where('ch.warranty_claim_id', $claimId)
            ->orderBy('ch.created_at', 'ASC')
            ->orderBy('ch.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getAvailableTransitions(string $currentStatus): array
    {
        return self::TRANSITIONS[$currentStatus] ?? [];
    }


    private function notifyClaimStatus(
        int $claimId,
        string $status,
        ?string $note
    ): void {
        try {
            $claim = $this->db->table('warranty_claims wc')
                ->select(
                    'wc.id AS claim_id,
                 wc.warranty_id,
                 c.name AS customer_name,
                 c.whatsapp AS customer_whatsapp'
                )
                ->join('warranties w', 'w.id = wc.warranty_id')
                ->join('sales s', 's.id = w.sales_id')
                ->join('customers c', 'c.id = s.customer_id')
                ->where('wc.id', $claimId)
                ->get()
                ->getRowArray();

            if (
                ! $claim
                || empty($claim['customer_whatsapp'])
            ) {
                log_message(
                    'warning',
                    "Notifikasi klaim {$claimId} dilewati: data pelanggan tidak lengkap."
                );
                return;
            }

            $templateService = new NotificationTemplateService();
            $notificationService = new NotificationService();

            $message = $templateService->claimStatusChanged([
                'customer_name' => $claim['customer_name'],
                'claim_id'      => $claim['claim_id'],
                'status'        => $status,
                'note'          => $note,
            ]);

            $notificationService->send(
                $claim['customer_whatsapp'],
                'claim_status_changed',
                $message,
                null,
                (int) $claim['claim_id']
            );
        } catch (\Throwable $e) {
            log_message(
                'error',
                'Gagal mengirim notifikasi klaim: ' . $e->getMessage()
            );
        }
    }
}
