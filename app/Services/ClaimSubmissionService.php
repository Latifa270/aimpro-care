<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;
use Throwable;

class ClaimSubmissionService
{
    private BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function submit(
        string $token,
        array $data,
        UploadedFile $photo,
        ?UploadedFile $video = null
    ): int {
        $movedFiles = [];

        $this->db->transBegin();

        try {
            $warranty = $this->db->query(
                'SELECT
                    w.id,
                    w.status,
                    w.end_date,
                    w.warranty_number,
                    c.name AS customer_name,
                    c.whatsapp AS registered_whatsapp
                 FROM warranties w
                 JOIN sales s ON s.id = w.sales_id
                 JOIN customers c ON c.id = s.customer_id
                 WHERE w.qr_token = ?
                 FOR UPDATE',
                [$token]
            )->getRowArray();

            if (! $warranty) {
                throw new RuntimeException('Garansi tidak ditemukan.');
            }

            $damageDate = $data['damage_start_date'] ?? '';

            $dateObject = \DateTime::createFromFormat('!Y-m-d', $damageDate);

            if (
                ! $dateObject
                || $dateObject->format('Y-m-d') !== $damageDate
            ) {
                throw new RuntimeException('Tanggal mulai kerusakan tidak valid.');
            }

            if ($damageDate > date('Y-m-d')) {
                throw new RuntimeException(
                    'Tanggal mulai kerusakan tidak boleh melebihi hari ini.'
                );
            }

            $handoverMethod = trim($data['handover_method'] ?? '');
            $shippingAddress = trim($data['shipping_address'] ?? '');

            if (! in_array($handoverMethod, ['Kurir', 'Antar Langsung'], true)) {
                throw new RuntimeException('Metode penyerahan barang tidak valid.');
            }

            if ($handoverMethod === 'Kurir' && $shippingAddress === '') {
                throw new RuntimeException(
                    'Alamat pengiriman wajib diisi jika memilih metode Kurir.'
                );
            }

            if (mb_strlen($shippingAddress) > 2000) {
                throw new RuntimeException(
                    'Alamat pengiriman maksimal 2000 karakter.'
                );
            }

            if ($warranty['end_date'] < date('Y-m-d')) {
                throw new RuntimeException('Masa garansi telah berakhir.');
            }

            if ($warranty['status'] !== 'Garansi Aktif') {
                throw new RuntimeException('Garansi tidak aktif.');
            }

            $submittedWhatsapp = $this->normalizeWhatsapp(
                (string) $data['whatsapp']
            );

            $registeredWhatsapp = $this->normalizeWhatsapp(
                (string) $warranty['registered_whatsapp']
            );

            if ($submittedWhatsapp !== $registeredWhatsapp) {
                throw new RuntimeException(
                    'Nomor WhatsApp tidak cocok dengan data pelanggan.'
                );
            }

            $existingClaim = $this->db->table('warranty_claims')
                ->where('warranty_id', $warranty['id'])
                ->whereNotIn('status', ['Klaim Ditolak', 'Selesai'])
                ->countAllResults();

            if ($existingClaim > 0) {
                throw new RuntimeException(
                    'Masih ada klaim aktif untuk garansi ini.'
                );
            }

            $claimDirectory = WRITEPATH . 'uploads/claims/';

            if (
                ! is_dir($claimDirectory)
                && ! mkdir($claimDirectory, 0755, true)
                && ! is_dir($claimDirectory)
            ) {
                throw new RuntimeException(
                    'Direktori bukti klaim tidak dapat dibuat.'
                );
            }

            $photoExtension = $photo->guessExtension();

            if (! $photoExtension) {
                throw new RuntimeException('Ekstensi foto tidak dapat dikenali.');
            }

            $photoName = bin2hex(random_bytes(16)) . '.' . $photoExtension;

            if (! $photo->move($claimDirectory, $photoName)) {
                throw new RuntimeException('Gagal menyimpan foto bukti.');
            }

            $photoPath = $claimDirectory . $photoName;
            $movedFiles[] = $photoPath;

            if (! is_file($photoPath)) {
                throw new RuntimeException(
                    'File foto tidak ditemukan setelah dipindahkan.'
                );
            }

            $serverPhotoMime = mime_content_type($photoPath);

            if ($serverPhotoMime === false) {
                throw new RuntimeException(
                    'Tipe file foto tidak dapat diverifikasi.'
                );
            }

            $allowedPhotoMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
            ];

            if (! in_array($serverPhotoMime, $allowedPhotoMimeTypes, true)) {
                throw new RuntimeException(
                    'Format foto harus JPG, JPEG, PNG, atau WebP.'
                );
            }

            $videoPath = null;
            $allowedVideoMimeTypes = [
                'video/mp4',
                'video/webm',
                'video/quicktime',
            ];

            if ($video && $video->getError() !== UPLOAD_ERR_NO_FILE) {
                if (! $video->isValid() || $video->hasMoved()) {
                    throw new RuntimeException('Video bukti tidak valid.');
                }

                if ($video->getSizeByUnit('mb') > 20) {
                    throw new RuntimeException('Ukuran video maksimal 20 MB.');
                }

                if (! in_array($video->getMimeType(), $allowedVideoMimeTypes, true)) {
                    throw new RuntimeException(
                        'Format video harus MP4, WebM, atau MOV.'
                    );
                }

                $videoExtension = $video->guessExtension();

                if (! $videoExtension) {
                    throw new RuntimeException('Ekstensi video tidak dapat dikenali.');
                }

                $videoName = bin2hex(random_bytes(16)) . '.' . $videoExtension;

                if (! $video->move($claimDirectory, $videoName)) {
                    throw new RuntimeException('Gagal menyimpan video bukti.');
                }

                $videoPath = $claimDirectory . $videoName;
                $movedFiles[] = $videoPath;

                if (! is_file($videoPath)) {
                    throw new RuntimeException(
                        'File video tidak ditemukan setelah dipindahkan.'
                    );
                }

                $serverVideoMime = mime_content_type($videoPath);

                if ($serverVideoMime === false) {
                    throw new RuntimeException(
                        'Tipe file video tidak dapat diverifikasi.'
                    );
                }

                if (! in_array($serverVideoMime, $allowedVideoMimeTypes, true)) {
                    throw new RuntimeException(
                        'Format video harus MP4, WebM, atau MOV.'
                    );
                }
            }

            $now = date('Y-m-d H:i:s');

            $inserted = $this->db->table('warranty_claims')->insert([
                'warranty_id' => $warranty['id'],
                'complaint' => trim($data['complaint']),
                'damage_start_date' => $damageDate,
                'damage_photo_path' => 'uploads/claims/' . $photoName,
                'damage_video_path' => $videoPath
                    ? 'uploads/claims/' . basename($videoPath)
                    : null,
                'whatsapp' => trim($data['whatsapp']),
                'handover_method' => $handoverMethod,
                'shipping_address' => $handoverMethod === 'Kurir'
                    ? $shippingAddress
                    : null,
                'terms_agreed' => (int) ($data['terms_agreed'] ?? 0),
                'status' => 'Pengajuan Baru',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if (! $inserted) {
                throw new RuntimeException('Gagal menyimpan pengajuan klaim.');
            }

            $claimId = (int) $this->db->insertID();

            if ($claimId <= 0) {
                throw new RuntimeException('ID klaim tidak berhasil dibuat.');
            }

            if ($this->db->transStatus() === false) {
                throw new RuntimeException('Transaksi klaim gagal.');
            }

            $this->db->transCommit();

            try {
                $templateService = new NotificationTemplateService();
                $notificationService = new NotificationService();

                $message = $templateService->claimSubmitted([
                    'customer_name' => $warranty['customer_name'],
                    'claim_id'      => $claimId,
                ]);

                $notificationService->send(
                    $submittedWhatsapp,
                    'claim_submitted',
                    $message,
                    null,
                    $claimId
                );
            } catch (Throwable $e) {
                log_message(
                    'error',
                    'Notifikasi pengajuan klaim gagal: ' . $e->getMessage()
                );
            }

            return $claimId;
        } catch (Throwable $e) {
            $this->db->transRollback();

            foreach ($movedFiles as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }

            throw $e;
        }
    }

    private function normalizeWhatsapp(string $number): string
    {
        $number = preg_replace('/[\s\-()]/', '', $number);

        if (str_starts_with($number, '+')) {
            $number = substr($number, 1);
        }

        return $number;
    }
}
