<?php

namespace App\Services;

use App\Libraries\ActivityLogger;
use App\Models\ProductUnitModel;
use App\Models\QcChecklistItemModel;
use App\Models\QcDocumentationModel;
use App\Models\QcInspectionModel;
use RuntimeException;
use Throwable;

class QcInspectionService
{
    protected ProductUnitModel $productUnitModel;
    protected QcInspectionModel $qcInspectionModel;
    protected QcChecklistItemModel $qcChecklistItemModel;
    protected QcDocumentationModel $qcDocumentationModel;

    public function __construct()
    {
        $this->productUnitModel = new ProductUnitModel();
        $this->qcInspectionModel = new QcInspectionModel();
        $this->qcChecklistItemModel = new QcChecklistItemModel();
        $this->qcDocumentationModel = new QcDocumentationModel();
    }

    public function getInspection(int $inspectionId): ?array
    {
        return $this->qcInspectionModel
            ->select('
                qc_inspections.*,
                product_units.serial_number,
                products.name AS product_name,
                products.brand,
                products.model,
                users.name AS qc_officer
            ')
            ->join(
                'product_units',
                'product_units.id = qc_inspections.product_unit_id'
            )
            ->join(
                'products',
                'products.id = product_units.product_id'
            )
            ->join(
                'users',
                'users.id = qc_inspections.user_id'
            )
            ->where('qc_inspections.id', $inspectionId)
            ->first();
    }

    public function createInspection(
        int $unitId,
        int $userId,
        string $physicalCondition,
        string $completeness
    ): int {
        $db = db_connect();
        $db->transBegin();

        try {
            $unit = $db->query(
                'SELECT *
                 FROM product_units
                 WHERE id = ?
                   AND status = ?
                 FOR UPDATE',
                [$unitId, 'Menunggu QC']
            )->getRowArray();

            if (! $unit) {
                throw new RuntimeException(
                    'Unit tidak ditemukan atau sudah diproses.'
                );
            }

            $inspectionId = $this->qcInspectionModel->insert([
                'product_unit_id'    => $unit['id'],
                'user_id'            => $userId,
                'inspection_date'    => date('Y-m-d H:i:s'),
                'physical_condition' => $physicalCondition,
                'completeness'       => $completeness,
                'result'             => 'Lolos Qc',
            ]);

            if (! $inspectionId) {
                throw new RuntimeException(
                    'Pemeriksaan QC gagal disimpan.'
                );
            }

            if ($db->transStatus() === false) {
                throw new RuntimeException(
                    'Transaksi pemeriksaan QC gagal.'
                );
            }

            $db->transCommit();

            ActivityLogger::log(
                'CREATE_QC_INSPECTION',
                'Memulai pemeriksaan QC untuk serial number '
                    . $unit['serial_number']
                    . '.'
            );

            return $inspectionId;
        } catch (Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function saveChecklist(
        int $inspectionId,
        array $items,
        string $serialNumber
    ): void {
        $checklistNames = [
            1 => 'Kemasan dalam kondisi baik',
            2 => 'Produk tidak lecet atau retak',
            3 => 'Nomor seri produk sesuai dengan kemasan',
            4 => 'Kelengkapan produk sesuai',
            5 => 'Produk dapat dinyalakan',
            6 => 'Fungsi dasar berjalan',
            7 => 'Tidak ditemukan kerusakan',
        ];

        $db = db_connect();
        $db->transBegin();

        try {
            $this->qcChecklistItemModel
                ->where('qc_inspection_id', $inspectionId)
                ->delete();

            foreach ($checklistNames as $key => $itemName) {
                $inserted = $this->qcChecklistItemModel->insert([
                    'qc_inspection_id' => $inspectionId,
                    'item_name'        => $itemName,
                    'result'           => $items[$key] === '1',
                    'note'             => null,
                ]);

                if (! $inserted) {
                    throw new RuntimeException(
                        'Gagal menyimpan item checklist QC.'
                    );
                }
            }

            if ($db->transStatus() === false) {
                throw new RuntimeException(
                    'Transaksi checklist QC gagal.'
                );
            }

            $db->transCommit();

            ActivityLogger::log(
                'SAVE_QC_CHECKLIST',
                'Menyimpan 7 item checklist QC untuk serial number '
                    . $serialNumber
                    . '.'
            );
        } catch (Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function saveDocumentation(
        int $inspectionId,
        array $files,
        string $serialNumber
    ): void {
        $uploadPath = WRITEPATH . 'uploads/qc/';

        if (
            ! is_dir($uploadPath)
            && ! mkdir($uploadPath, 0755, true)
            && ! is_dir($uploadPath)
        ) {
            throw new RuntimeException(
                'Direktori penyimpanan dokumentasi tidak tersedia.'
            );
        }

        $db = db_connect();
        $movedPaths = [];

        $db->transBegin();

        try {
            foreach ($files as $field => $fileData) {
                $file = $fileData['file'];
                $isRequired = $fileData['required'] ?? true;

                if (! $file || ! $file->isValid()) {
                    if (! $isRequired) {
                        continue;   // ← skip video yang tidak diupload
                    }

                    throw new RuntimeException(
                        'File ' . $fileData['type'] . ' tidak valid.'
                    );
                }

                $newName = $file->getRandomName();

                if (! $file->move($uploadPath, $newName)) {
                    throw new RuntimeException(
                        'File ' . $fileData['type'] . ' gagal disimpan.'
                    );
                }

                $absolutePath = $uploadPath . $newName;
                $movedPaths[] = $absolutePath;

                if (! is_file($absolutePath)) {
                    throw new RuntimeException(
                        'File ' . $fileData['type'] . ' tidak ditemukan setelah dipindahkan.'
                    );
                }

                $serverMimeType = mime_content_type($absolutePath);

                if ($serverMimeType === false) {
                    throw new RuntimeException(
                        'Tipe file ' . $fileData['type'] . ' tidak dapat diverifikasi.'
                    );
                }

                $document = [
                    'qc_inspection_id' => $inspectionId,
                    'document_type'    => $fileData['type'],
                    'file_path'        => 'uploads/qc/' . $newName,
                    'file_name'        => basename($file->getClientName()),
                    'mime_type'        => $serverMimeType,
                    'file_size'        => filesize($absolutePath),
                ];

                $inserted = $this->qcDocumentationModel->insert($document);

                if (! $inserted) {
                    throw new RuntimeException(
                        'Metadata ' . $fileData['type'] . ' gagal disimpan.'
                    );
                }
            }

            if ($db->transStatus() === false) {
                throw new RuntimeException(
                    'Transaksi dokumentasi QC gagal.'
                );
            }

            $db->transCommit();

            ActivityLogger::log(
                'UPLOAD_QC_DOCUMENTATION',
                'Mengunggah foto produk, foto nomor seri, dan video unboxing untuk serial number '
                    . $serialNumber
                    . '.'
            );
        } catch (Throwable $e) {
            $db->transRollback();

            foreach ($movedPaths as $path) {
                if (is_file($path)) {
                    @unlink($path);
                }
            }

            throw $e;
        }
    }

    public function saveResult(
        int $inspectionId,
        array $inspection,
        string $result,
        ?string $notes,
        array $checklist
    ): void {
        $hasFailedChecklist = false;

        foreach ($checklist as $item) {
            if (
                $item['result'] === false
                || $item['result'] === 0
                || $item['result'] === '0'
            ) {
                $hasFailedChecklist = true;
                break;
            }
        }

        $notes = trim((string) $notes);

        if (
            $hasFailedChecklist
            && $result === 'Lolos QC'
            && $notes === ''
        ) {
            throw new RuntimeException(
                'Alasan keputusan wajib diisi karena terdapat checklist yang tidak memenuhi kriteria.'
            );
        }

        if (
            ! $hasFailedChecklist
            || $result !== 'Lolos QC'
        ) {
            $notes = null;
        }

        $unitStatus = match ($result) {
            'Lolos QC' => 'Siap Dijual',
            'Tidak Lolos QC' => 'Tidak Lolos QC',
            'Perlu Pemeriksaan Ulang' => 'Perlu Pemeriksaan Ulang',
        };

        $db = db_connect();
        $db->transBegin();

        try {
            $unit = $db->query(
                'SELECT *
                 FROM product_units
                 WHERE id = ?
                 FOR UPDATE',
                [$inspection['product_unit_id']]
            )->getRowArray();

            if (! $unit) {
                throw new RuntimeException(
                    'Unit produk tidak ditemukan.'
                );
            }

            if ($unit['status'] !== 'Menunggu QC') {
                throw new RuntimeException(
                    'Status unit sudah berubah. Hasil QC tidak dapat disimpan.'
                );
            }

            $inspectionUpdated = $this->qcInspectionModel->update(
                $inspectionId,
                [
                    'result' => $result,
                    'notes'  => $notes,
                ]
            );

            if (! $inspectionUpdated) {
                throw new RuntimeException(
                    'Hasil inspeksi gagal diperbarui.'
                );
            }

            $unitUpdated = $this->productUnitModel->update(
                $inspection['product_unit_id'],
                [
                    'status' => $unitStatus,
                ]
            );

            if (! $unitUpdated) {
                throw new RuntimeException(
                    'Status unit gagal diperbarui.'
                );
            }

            if ($db->transStatus() === false) {
                throw new RuntimeException(
                    'Transaksi database gagal.'
                );
            }

            $db->transCommit();
        } catch (Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function getChecklist(int $inspectionId): array
    {
        return $this->qcChecklistItemModel
            ->where('qc_inspection_id', $inspectionId)
            ->findAll();
    }

    public function getDocuments(int $inspectionId): array
    {
        return $this->qcDocumentationModel
            ->where('qc_inspection_id', $inspectionId)
            ->findAll();
    }

    public function hasCompleteDocuments(int $inspectionId): bool
    {
        $documents = $this->getDocuments($inspectionId);

        $documentTypes = array_column($documents, 'document_type');

        return in_array('Foto Nomor Seri', $documentTypes, true)
            && in_array('Foto Produk', $documentTypes, true);
    }

    public function hasFailedChecklist(array $checklist): bool
    {
        foreach ($checklist as $item) {
            if (
                $item['result'] === false
                || $item['result'] === 0
                || $item['result'] === '0'
            ) {
                return true;
            }
        }

        return false;
    }
}
