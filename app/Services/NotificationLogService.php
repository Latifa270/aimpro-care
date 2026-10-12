<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use InvalidArgumentException;

class NotificationLogService
{
    private BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function record(array $data): int
    {
        $status = $data['status'] ?? null;

        if (! in_array($status, ['Berhasil', 'Gagal'], true)) {
            throw new InvalidArgumentException(
                'Status notifikasi tidak valid.'
            );
        }

        $warrantyId = $data['warranty_id'] ?? null;
        $claimId = $data['warranty_claim_id'] ?? null;

        if ($warrantyId === null && $claimId === null) {
            throw new InvalidArgumentException(
                'Notifikasi harus terhubung dengan garansi atau klaim.'
            );
        }

        $now = date('Y-m-d H:i:s');

        $this->db->table('notifications')->insert([
            'warranty_id'       => $warrantyId,
            'warranty_claim_id' => $claimId,
            'destination'       => $data['destination'],
            'template'          => $data['template'],
            'sent_at'           => $status === 'Berhasil' ? ($data['sent_at'] ?? $now) : null,
            'status'            => $status,
            'gateway_response'  => $data['gateway_response'] ?? null,
            'created_at'        => $now,
            'updated_at'        => null,
        ]);

        return (int) $this->db->insertID();
    }
}
