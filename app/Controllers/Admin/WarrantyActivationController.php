<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductUnitModel;
use App\Services\WarrantyActivationService;
use App\Services\WarrantyCardService;
use Throwable;

class WarrantyActivationController extends BaseController
{
    protected ProductUnitModel $productUnitModel;
    protected WarrantyActivationService $activationService;
    protected WarrantyCardService $warrantyCardService;

    public function __construct()
    {
        $this->productUnitModel = new ProductUnitModel();
        $this->activationService = new WarrantyActivationService();
        $this->warrantyCardService = new WarrantyCardService();
    }

    public function index()
    {
        $units = $this->productUnitModel
            ->select('product_units.*, products.name AS product_name, products.brand, products.model')
            ->join(
                'products',
                'products.id = product_units.product_id'
            )
            ->where('product_units.status', 'Siap Dijual')
            ->orderBy('product_units.created_at', 'DESC')
            ->findAll();

        return view('admin/warranty_activation/index', [
            'title'  => 'Aktivasi Garansi',
            'header' => 'Aktivasi Garansi',
            'units'  => $units,
        ]);
    }

    public function create(int $id)
    {
        $unit = $this->productUnitModel
            ->select('product_units.*, products.name AS product_name, products.brand, products.model, products.warranty_duration_months')
            ->join(
                'products',
                'products.id = product_units.product_id'
            )
            ->where('product_units.id', $id)
            ->where('product_units.status', 'Siap Dijual')
            ->first();

        if (!$unit) {
            return redirect()
                ->to('/admin/warranty-activation')
                ->with('error', 'Unit tidak ditemukan atau tidak siap dijual.');
        }

        return view('admin/warranty_activation/create', [
            'title' => 'Form Aktivasi Garansi',
            'unit' => $unit,
        ]);
    }

    public function store()
    {
        $rules = [
            'product_unit_id' => [
                'label' => 'Unit produk',
                'rules' => 'required|is_natural_no_zero',
            ],
            'name' => [
                'label' => 'Nama pelanggan',
                'rules' => 'required|string|max_length[50]',
            ],
            'whatsapp' => [
                'label' => 'Nomor WhatsApp',
                'rules' => 'required|string|max_length[20]|regex_match[/^[0-9+\-\s()]{8,20}$/]',
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'permit_empty|valid_email|max_length[100]',
            ],
            'address' => [
                'label' => 'Alamat pelanggan',
                'rules' => 'required|string',
            ],
            'purchase_date' => [
                'label' => 'Tanggal pembelian',
                'rules' => 'required|valid_date[Y-m-d]',
            ],
            'invoice_number' => [
                'label' => 'Nomor invoice',
                'rules' => 'required|string|max_length[100]',
            ],
            'marketplace' => [
                'label' => 'Marketplace',
                'rules' => 'permit_empty|string|max_length[100]',
            ],
            'marketplace_order_number' => [
                'label' => 'Nomor pesanan marketplace',
                'rules' => 'permit_empty|string|max_length[100]',
            ],
            'sales_note' => [
                'label' => 'Catatan penjualan',
                'rules' => 'permit_empty|string',
            ],
            'duration_months' => [
                'label' => 'Durasi garansi',
                'rules' => 'required|is_natural_no_zero|less_than_equal_to[120]',
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $receipt = $this->request->getFile('receipt');
        $receiptPath = null;

        if ($receipt && $receipt->isValid() && !$receipt->hasMoved()) {
            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'application/pdf',
            ];

            if (!in_array($receipt->getMimeType(), $allowedMimeTypes, true)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Bukti pembelian harus berupa JPG, PNG, WEBP, atau PDF.');
            }

            if ($receipt->getSizeByUnit('kb') > 5120) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Ukuran bukti pembelian maksimal 5 MB.');
            }
        } elseif ($receipt && $receipt->getError() !== UPLOAD_ERR_NO_FILE) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Bukti pembelian gagal diunggah.');
        }

        $uploadDirectory = WRITEPATH . 'uploads/receipts';

        if (
            !is_dir($uploadDirectory) &&
            !mkdir($uploadDirectory, 0755, true) &&
            !is_dir($uploadDirectory)
        ) {
            log_message(
                'error',
                'Gagal membuat direktori bukti pembelian: ' . $uploadDirectory
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Direktori penyimpanan bukti pembelian tidak dapat dibuat.'
                );
        }

        try {
            if ($receipt && $receipt->isValid() && !$receipt->hasMoved()) {
                $newName = $receipt->getRandomName();
                $receipt->move($uploadDirectory, $newName);
                $receiptPath = 'uploads/receipts/' . $newName;
                $absolutePath = $uploadDirectory . '/' . $newName;

                if (! is_file($absolutePath)) {
                    throw new \RuntimeException(
                        'File bukti pembelian tidak ditemukan setelah dipindahkan.'
                    );
                }

                $serverMimeType = mime_content_type($absolutePath);

                if ($serverMimeType === false) {
                    @unlink($absolutePath);
                    $receiptPath = null;

                    throw new \RuntimeException(
                        'Tipe file bukti pembelian tidak dapat diverifikasi.'
                    );
                }

                $allowedServerMimeTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                    'application/pdf',
                ];

                if (! in_array($serverMimeType, $allowedServerMimeTypes, true)) {
                    @unlink($absolutePath);
                    $receiptPath = null;

                    throw new \RuntimeException(
                        'Tipe file bukti pembelian tidak valid. Silakan unggah JPG, PNG, WEBP, atau PDF.'
                    );
                }
            }

            $customerData = [
                'name'     => trim($this->request->getPost('name')),
                'whatsapp' => trim($this->request->getPost('whatsapp')),
                'email'    => trim((string) $this->request->getPost('email')),
                'address'  => trim($this->request->getPost('address')),
            ];

            $saleData = [
                'purchase_date'            => $this->request->getPost('purchase_date'),
                'invoice_number'           => trim($this->request->getPost('invoice_number')),
                'marketplace'              => trim((string) $this->request->getPost('marketplace')),
                'marketplace_order_number' => trim((string) $this->request->getPost('marketplace_order_number')),
                'receipt_path'             => $receiptPath,
                'sales_note'               => trim((string) $this->request->getPost('sales_note')),
            ];

            $result = $this->activationService->activate(
                (int) $this->request->getPost('product_unit_id'),
                $customerData,
                $saleData,
                (int) $this->request->getPost('duration_months')
            );

            $warrantyId = (int) ($result['warranty_id'] ?? 0);

            if ($warrantyId <= 0) {
                throw new \RuntimeException(
                    'Aktivasi berhasil tetapi ID garansi tidak ditemukan.'
                );
            }

            $cardGenerated = true;

            try {
                $this->warrantyCardService->generate($warrantyId);
            } catch (Throwable $cardException) {
                $cardGenerated = false;

                log_message(
                    'error',
                    'Garansi berhasil diaktifkan tetapi kartu digital gagal dibuat. '
                        . 'Warranty ID: ' . $warrantyId
                        . '. Error: ' . $cardException->getMessage()
                );
            }

            $message = 'Garansi berhasil diaktifkan. Nomor garansi: '
                . $result['warranty_number'];

            if (! $cardGenerated) {
                $message .=
                    ' Kartu digital belum berhasil dibuat dan dapat dibuat ulang dari halaman kartu garansi.';
            }

            return redirect()
                ->to(
                    site_url('admin/warranty-cards/' . $warrantyId)
                )
                ->with('success', $message);
        } catch (Throwable $e) {
            if ($receiptPath !== null) {
                $fullPath = WRITEPATH . $receiptPath;

                if (is_file($fullPath)) {
                    unlink($fullPath);
                }
            }

            log_message(
                'error',
                'Aktivasi garansi gagal: ' . $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Penjualan gagal diproses. Silakan periksa data dan coba kembali.'
                );
        }
    }
}
