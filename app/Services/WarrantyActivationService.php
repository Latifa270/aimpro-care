<?php

namespace App\Services;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\ProductUnitModel;
use App\Models\SalesModel;
use App\Models\WarrantyModel;
use DateTimeImmutable;
use RuntimeException;
use Throwable;

class WarrantyActivationService
{
    protected CustomerModel $customerModel;
    protected ProductModel $productModel;
    protected ProductUnitModel $productUnitModel;
    protected SalesModel $salesModel;
    protected WarrantyModel $warrantyModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->productModel = new ProductModel();
        $this->productUnitModel = new ProductUnitModel();
        $this->salesModel = new SalesModel();
        $this->warrantyModel = new WarrantyModel();
    }

    public function activate(
        int $productUnitId,
        array $customerData,
        array $saleData,
        int $durationMonths
    ): array {
        $db = \Config\Database::connect();

        if ($durationMonths < 1 || $durationMonths > 120) {
            throw new RuntimeException('Durasi garansi tidak valid.');
        }

        $purchaseDate = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $saleData['purchase_date']
        );

        $dateErrors = DateTimeImmutable::getLastErrors();

        if (
            !$purchaseDate ||
            ($dateErrors !== false && (
                $dateErrors['warning_count'] > 0 ||
                $dateErrors['error_count'] > 0
            )) ||
            $purchaseDate->format('Y-m-d') !== $saleData['purchase_date']
        ) {
            throw new RuntimeException(
                'Tanggal pembelian tidak valid.'
            );
        }

        $today = new DateTimeImmutable('today');

        if ($purchaseDate > $today) {
            throw new RuntimeException(
                'Tanggal pembelian tidak boleh melebihi hari ini.'
            );
        }

        $db->transBegin();

        try {
            $unit = $db->query(
                'SELECT * FROM product_units
                 WHERE id = ? AND deleted_at IS NULL
                 FOR UPDATE',
                [$productUnitId]
            )->getRowArray();

            if (!$unit) {
                throw new RuntimeException(
                    'Unit produk tidak ditemukan.'
                );
            }

            if ($unit['status'] !== 'Siap Dijual') {
                throw new RuntimeException(
                    'Unit tidak dapat dijual karena statusnya bukan Siap Dijual.'
                );
            }

            $product = $this->productModel->find(
                $unit['product_id']
            );

            if (!$product) {
                throw new RuntimeException(
                    'Data produk tidak ditemukan.'
                );
            }

            $whatsapp = trim($customerData['whatsapp']);
            $customer = $this->customerModel
                ->where('whatsapp', $whatsapp)
                ->first();

            if ($customer) {
                $customerId = (int) $customer['id'];
            } else {
                $customerInserted = $this->customerModel->insert([
                    'name'     => $customerData['name'],
                    'whatsapp' => $whatsapp,
                    'email'    => $customerData['email'] ?? null,
                    'address'  => $customerData['address'],
                ]);

                if ($customerInserted === false) {
                    throw new RuntimeException(
                        'Gagal menyimpan data pelanggan.'
                    );
                }

                $customerId = (int) $db->insertID();

                if ($customerId < 1) {
                    throw new RuntimeException(
                        'Gagal menyimpan data pelanggan.'
                    );
                }
            }

            $saleInserted = $this->salesModel->insert([
                'product_unit_id'          => $productUnitId,
                'customer_id'              => $customerId,
                'purchase_date'            => $saleData['purchase_date'],
                'invoice_number'           => $saleData['invoice_number'],
                'marketplace'              => $saleData['marketplace'] ?? null,
                'marketplace_order_number' => $saleData['marketplace_order_number'] ?? null,
                'receipt_path'             => $saleData['receipt_path'] ?? null,
                'sales_note'               => $saleData['sales_note'] ?? null,
            ]);

            if ($saleInserted === false) {
                throw new RuntimeException(
                    'Gagal menyimpan transaksi penjualan.'
                );
            }

            $salesId = (int) $db->insertID();

            if ($salesId < 1) {
                throw new RuntimeException(
                    'Gagal menyimpan transaksi penjualan.'
                );
            }

            $year = date(
                'Y',
                strtotime($saleData['purchase_date'])
            );

            $warrantyNumber = sprintf(
                'AIM-WRT-%s-%06d',
                $year,
                $salesId
            );

            $qrToken = bin2hex(random_bytes(32));
            $startDate = $purchaseDate;

            $endDate = $this->calculateWarrantyEndDate(
                $saleData['purchase_date'],
                $durationMonths
            );

            $warrantyInserted = $this->warrantyModel->insert([
                'sales_id'        => $salesId,
                'warranty_number' => $warrantyNumber,
                'start_date'      => $startDate->format('Y-m-d'),
                'end_date'        => $endDate,
                'duration_months' => $durationMonths,
                'qr_token'        => $qrToken,
                'status'          => 'Garansi Aktif',
            ]);

            if ($warrantyInserted === false) {
                throw new RuntimeException(
                    'Gagal membuat data garansi.'
                );
            }

            $warrantyId = (int) $db->insertID();

            if ($warrantyId < 1) {
                throw new RuntimeException(
                    'Gagal membuat data garansi.'
                );
            }

            $updated = $db->table('product_units')
                ->where('id', $productUnitId)
                ->where('status', 'Siap Dijual')
                ->update([
                    'status'     => 'Terjual',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            if (!$updated || $db->affectedRows() !== 1) {
                throw new RuntimeException(
                    'Gagal memperbarui status unit.'
                );
            }

            if ($db->transStatus() === false) {
                throw new RuntimeException(
                    'Transaksi database gagal.'
                );
            }
            $db->transCommit();

            try {
                $templateService = new NotificationTemplateService();
                $notificationService = new NotificationService();

                $message = $templateService->warrantyActivated([
                    'customer_name'   => $customerData['name'],
                    'warranty_number' => $warrantyNumber,
                    'serial_number'   => $unit['serial_number'],
                    'start_date'      => $startDate->format('d-m-Y'),
                    'end_date'        => (new DateTimeImmutable($endDate))->format('d-m-Y'),
                    'duration_months' => $durationMonths,
                ]);

                $notificationService->send(
                    $whatsapp,
                    'warranty_activated',
                    $message,
                    $warrantyId
                );

                log_message(
                    'info',
                    'Notifikasi aktivasi garansi berhasil dikirim. Warranty ID: '
                        . $warrantyId
                );
            } catch (Throwable $e) {
                log_message(
                    'error',
                    'Notifikasi aktivasi garansi gagal. Warranty ID: '
                        . $warrantyId
                        . '. Error: '
                        . $e->getMessage()
                );
            }

            return [
                'sales_id'        => $salesId,
                'warranty_id'     => $warrantyId,
                'customer_id'     => $customerId,
                'warranty_number' => $warrantyNumber,
                'qr_token'        => $qrToken,
                'start_date'      => $startDate->format('Y-m-d'),
                'end_date'        => $endDate,
                'duration_months' => $durationMonths,
            ];
        } catch (Throwable $e) {
            $db->transRollback();

            throw $e;
        }
    }

    protected function calculateWarrantyEndDate(
        string $purchaseDate,
        int $durationMonths
    ): string {
        $startDate = new DateTimeImmutable($purchaseDate);

        $endMonth = $startDate
            ->modify('first day of this month')
            ->modify("+{$durationMonths} months");

        $day = min(
            (int) $startDate->format('d'),
            (int) $endMonth->format('t')
        );

        return $endMonth
            ->setDate(
                (int) $endMonth->format('Y'),
                (int) $endMonth->format('m'),
                $day
            )
            ->modify('-1 day')
            ->format('Y-m-d');
    }
}
