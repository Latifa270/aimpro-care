<?php

namespace App\Services;

class NotificationTemplateService
{
    public function warrantyActivated(array $data): string
    {
        return implode("\n", [
            'GARANSI PRODUK AIMpro CARE',
            '',
            'Halo ' . $data['customer_name'] . ',',
            '',
            'Terima kasih telah membeli produk kami.',
            'Garansi produk Anda berhasil diaktifkan.',
            '',
            'Detail Garansi',
            'Nomor Garansi: ' . $data['warranty_number'],
            'Nomor Seri: ' . $data['serial_number'],
            'Masa Garansi: ' . $data['duration_months'] . ' bulan',
            'Mulai: ' . $data['start_date'],
            'Berakhir: ' . $data['end_date'],
            '',
            'Simpan pesan ini sebagai bukti aktivasi garansi.',
            'Terima kasih telah mempercayai AIMpro Care.',
        ]);
    }

    public function warrantyExpiring(array $data): string
    {
        return implode("\n", [
            'Halo ' . $data['customer_name'] . ',',
            '',
            'Kami informasikan bahwa garansi produk Anda akan berakhir dalam '
                . $data['days_remaining'] . ' hari.',
            'Nomor Garansi: ' . $data['warranty_number'],
            'Tanggal Berakhir: ' . $data['end_date'],
            '',
            'Terimakasih telah menggunaka produk kami.',
        ]);
    }

    public function claimSubmitted(array $data): string
    {
        return implode("\n", [
            'Halo ' . $data['customer_name'] . ',',
            '',
            'Pengajuan klaim garansi Anda telah kami terima.',
            'Nomor Klaim: ' . $data['claim_id'],
            'Status: Pengajuan Baru',
            '',
            'Tim kami akan melakukan verivikasi lebih lanjut.',
        ]);
    }

    public function claimStatusChanged(array $data): string
    {
        return implode("\n", [
            'Halo ' . $data['customer_name'] . ',',
            '',
            'Terdapat pembaruan status klaim garansi Anda.',
            'Nomor Klaim: ' . $data['claim_id'],
            'Status Terbaru: ' . $data['status'],
            '',
            'Catatan: ' . ($data['note'] ?? '-'),
        ]);
    }
}
