<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use Dompdf\Dompdf;
use Dompdf\Options;
use RuntimeException;
use Throwable;

class WarrantyCardService
{
    private BaseConnection $db;
    private QrCodeService $qrCodeService;

    public function __construct()
    {
        $this->db = db_connect();
        $this->qrCodeService = new QrCodeService();
    }

    public function generate(int $warrantyId): array
    {
        $warranty = $this->getWarrantyData($warrantyId);

        if (! $warranty) {
            throw new RuntimeException('Data garansi tidak ditemukan.');
        }

        $token = $warranty['qr_token'] ?? '';

        if (! is_string($token) || strlen($token) !== 64) {
            throw new RuntimeException('Token QR Code tidak valid.');
        }

        $baseDirectory = WRITEPATH . 'uploads/warranties/';
        $qrDirectory = $baseDirectory . 'qr/';

        $this->ensureDirectory($baseDirectory, 'kartu garansi');
        $this->ensureDirectory($qrDirectory, 'QR Code');

        $qrFileName = 'warranty-' . $warrantyId . '.png';
        $pdfFileName = 'warranty-' . $warrantyId . '.pdf';
        $qrAbsolutePath = $qrDirectory . $qrFileName;
        $pdfAbsolutePath = $baseDirectory . $pdfFileName;
        $qrRelativePath = 'uploads/warranties/qr/' . $qrFileName;
        $pdfRelativePath = 'uploads/warranties/' . $pdfFileName;
        $publicUrl = base_url('garansi/' . rawurlencode($token));

        $this->qrCodeService->generate(
            $publicUrl,
            $qrAbsolutePath
        );

        try {
            $html = $this->buildHtml(
                $warranty,
                $qrAbsolutePath
            );

            $options = new Options();
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'Helvetica');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A5', 'landscape');
            $dompdf->render();

            $pdf = $dompdf->output();

            if (file_put_contents($pdfAbsolutePath, $pdf) === false) {
                throw new RuntimeException('Gagal menyimpan PDF kartu garansi.');
            }

            $updated = $this->db->table('warranties')
                ->where('id', $warrantyId)
                ->update([
                    'card_path' => $pdfRelativePath,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            if (! $updated && $this->db->affectedRows() === 0) {
                $current = $this->db->table('warranties')
                    ->select('card_path')
                    ->where('id', $warrantyId)
                    ->get()
                    ->getRowArray();

                if (($current['card_path'] ?? null) !== $pdfRelativePath) {
                    throw new RuntimeException('Gagal memperbarui lokasi kartu garansi.');
                }
            }

            return [
                'warranty_id' => $warrantyId,
                'card_path' => $pdfRelativePath,
                'qr_path' => $qrRelativePath,
                'public_url' => $publicUrl,
            ];
        } catch (Throwable $e) {
            if (is_file($pdfAbsolutePath)) {
                @unlink($pdfAbsolutePath);
            }

            throw $e;
        }
    }

    public function getDigitalCards(): array
    {
        return $this->db->table('warranties w')
            ->select([
                'w.id AS warranty_id',
                'w.warranty_number',
                'w.start_date',
                'w.end_date',
                'w.duration_months',
                'w.card_path',
                'w.status AS warranty_status',
                's.invoice_number',
                's.purchase_date',
                'c.name AS customer_name',
                'c.whatsapp AS customer_whatsapp',
                'p.name AS product_name',
                'p.brand AS product_brand',
                'p.model AS product_model',
                'pu.serial_number',
            ])
            ->join('sales s', 's.id = w.sales_id')
            ->join('customers c', 'c.id = s.customer_id')
            ->join('product_units pu', 'pu.id = s.product_unit_id')
            ->join('products p', 'p.id = pu.product_id')
            ->orderBy('w.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getDigitalCard(int $warrantyId): ?array
    {
        $warranty = $this->getWarrantyData($warrantyId);

        if (! $warranty) {
            return null;
        }

        $token = $warranty['qr_token'] ?? '';

        if (! is_string($token) || strlen($token) !== 64) {
            throw new RuntimeException('Token QR Code tidak valid.');
        }

        $qrRelativePath = 'uploads/warranties/qr/warranty-' .
            $warrantyId .
            '.png';

        $qrAbsolutePath = WRITEPATH . $qrRelativePath;

        if (! is_file($qrAbsolutePath)) {
            $qrDirectory = dirname($qrAbsolutePath);

            $this->ensureDirectory($qrDirectory, 'QR Code');

            $this->qrCodeService->generate(
                base_url('garansi/' . rawurlencode($token)),
                $qrAbsolutePath
            );
        }

        $qrData = file_get_contents($qrAbsolutePath);

        if ($qrData === false) {
            throw new RuntimeException(
                'QR Code kartu garansi tidak dapat dibaca.'
            );
        }

        $productName = trim(
            implode(' ', array_filter([
                $warranty['product_brand'] ?? null,
                $warranty['product_name'] ?? null,
                $warranty['product_model'] ?? null,
            ]))
        );

        return [
            'warranty_id' => (int) $warranty['warranty_id'],
            'warranty_number' => $warranty['warranty_number'],
            'start_date' => $warranty['start_date'],
            'end_date' => $warranty['end_date'],
            'duration_months' => (int) $warranty['duration_months'],
            'warranty_status' => $warranty['warranty_status'],
            'invoice_number' => $warranty['invoice_number'],
            'purchase_date' => $warranty['purchase_date'],
            'customer_name' => $warranty['customer_name'],
            'customer_whatsapp' => $warranty['customer_whatsapp'],
            'product_name' => $productName,
            'product_brand' => $warranty['product_brand'],
            'product_model' => $warranty['product_model'],
            'serial_number' => $warranty['serial_number'],
            'qr_token' => $token,
            'public_url' => base_url('garansi/' . rawurlencode($token)),
            'qr_data_uri' => 'data:image/png;base64,' . base64_encode($qrData),
            'card_path' => $warranty['card_path'] ?? null,
        ];
    }

    private function getWarrantyData(int $warrantyId): ?array
    {
        return $this->db->table('warranties w')
            ->select([
                'w.id AS warranty_id',
                'w.warranty_number',
                'w.start_date',
                'w.end_date',
                'w.duration_months',
                'w.qr_token',
                'w.card_path',
                'w.status AS warranty_status',
                's.invoice_number',
                's.purchase_date',
                'c.name AS customer_name',
                'c.whatsapp AS customer_whatsapp',
                'p.name AS product_name',
                'p.brand AS product_brand',
                'p.model AS product_model',
                'pu.serial_number',
            ])
            ->join('sales s', 's.id = w.sales_id')
            ->join('customers c', 'c.id = s.customer_id')
            ->join('product_units pu', 'pu.id = s.product_unit_id')
            ->join('products p', 'p.id = pu.product_id')
            ->where('w.id', $warrantyId)
            ->get()
            ->getRowArray() ?: null;
    }

    private function ensureDirectory(string $path, string $label): void
    {
        if (is_dir($path)) {
            return;
        }

        if (
            ! mkdir($path, 0755, true)
            && ! is_dir($path)
        ) {
            throw new RuntimeException(
                "Gagal membuat direktori {$label}."
            );
        }
    }

    private function buildHtml(array $data, string $qrPath): string
    {
        $escape = static fn($value): string =>
        esc((string) ($value ?? ''));

        $qrData = base64_encode(
            (string) file_get_contents($qrPath)
        );

        return '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <style>
                @page {
                    margin: 18px;
                }

                body {
                    font-family: Helvetica, sans-serif;
                    color: #222;
                    font-size: 12px;
                }

                .card {
                    border: 2px solid #1f4e79;
                    padding: 24px;
                    height: 470px;
                }

                .header {
                    background: #1f4e79;
                    color: #fff;
                    padding: 14px;
                    text-align: center;
                }

                .header h1 {
                    margin: 0;
                    font-size: 22px;
                }

                .content {
                    width: 100%;
                    margin-top: 20px;
                }

                .details {
                    width: 72%;
                    vertical-align: top;
                }

                .qr {
                    width: 28%;
                    text-align: center;
                    vertical-align: top;
                }

                .qr img {
                    width: 145px;
                    height: 145px;
                }

                .label {
                    color: #666;
                    width: 150px;
                }

                .row td {
                    padding: 7px 4px;
                    border-bottom: 1px solid #eee;
                }

                .number {
                    font-size: 16px;
                    font-weight: bold;
                    color: #1f4e79;
                }

                .footer {
                    margin-top: 20px;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                    font-size: 10px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="header">
                    <h1>AIMPRO CARE</h1>
                    <div>KARTU GARANSI DIGITAL</div>
                </div>

                <table class="content">
                    <tr>
                        <td class="details">
                            <p class="number">'
            . $escape($data['warranty_number']) .
            '</p>

                            <table>
                                <tr class="row">
                                    <td class="label">Nama Pelanggan</td>
                                    <td>' . $escape($data['customer_name']) . '</td>
                                </tr>
                                <tr class="row">
                                    <td class="label">Produk</td>
                                    <td>' . $escape(
                trim(
                    ($data['product_brand'] ?? '') . ' ' .
                        ($data['product_name'] ?? '') . ' ' .
                        ($data['product_model'] ?? '')
                )
            ) . '</td>
                                </tr>
                                <tr class="row">
                                    <td class="label">Nomor Seri</td>
                                    <td>' . $escape($data['serial_number']) . '</td>
                                </tr>
                                <tr class="row">
                                    <td class="label">Tanggal Pembelian</td>
                                    <td>' . $escape($data['purchase_date']) . '</td>
                                </tr>
                                <tr class="row">
                                    <td class="label">Garansi Berlaku</td>
                                    <td>' . $escape($data['start_date']) .
            ' s.d. ' . $escape($data['end_date']) .
            '</td>
                                </tr>
                                <tr class="row">
                                    <td class="label">Durasi</td>
                                    <td>' . $escape($data['duration_months']) . ' bulan</td>
                                </tr>
                                <tr class="row">
                                    <td class="label">Status</td>
                                    <td>' . $escape($data['warranty_status']) . '</td>
                                </tr>
                            </table>
                        </td>

                        <td class="qr">
                            <img src="data:image/png;base64,' . $qrData . '">
                            <p>Pindai untuk memeriksa garansi</p>
                        </td>
                    </tr>
                </table>

                <div class="footer">
                    Kartu garansi ini diterbitkan secara digital oleh AIMPRO CARE.
                    Simpan kartu ini sebagai bukti garansi produk.
                </div>
            </div>
        </body>
        </html>';
    }
}
