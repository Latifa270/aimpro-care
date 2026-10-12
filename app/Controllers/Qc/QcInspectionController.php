<?php

namespace App\Controllers\Qc;

use App\Controllers\BaseController;
use App\Models\ProductUnitModel;
use App\Services\QcInspectionService;
use RuntimeException;
use Throwable;

class QcInspectionController extends BaseController
{
    protected ProductUnitModel $productUnitModel;
    protected QcInspectionService $qcService;

    public function __construct()
    {
        $this->productUnitModel = new ProductUnitModel();
        $this->qcService = new QcInspectionService();
    }

    public function index()
    {
        $units = $this->productUnitModel
            ->select('product_units.*, products.name AS product_name, products.brand, products.model')
            ->join('products', 'products.id = product_units.product_id')
            ->where('product_units.status', 'Menunggu QC')
            ->orderBy('product_units.created_at', 'DESC')
            ->findAll();

        return view('qc/inspections/index', [
            'title'   => 'Pemeriksaan QC',
            'heading' => 'Pemeriksaan QC',
            'units'   => $units,
        ]);
    }

    public function create(int $unitId)
    {
        $unit = $this->productUnitModel
            ->select('product_units.*, products.name AS product_name, products.brand, products.model')
            ->join('products', 'products.id = product_units.product_id')
            ->where('product_units.id', $unitId)
            ->where('product_units.status', 'Menunggu QC')
            ->first();

        if (! $unit) {
            return redirect()
                ->to('/qc/inspections')
                ->with('error', 'Unit tidak ditemukan atau belum siap diperiksa.');
        }

        return view('qc/inspections/create', [
            'title' => 'Pemeriksaan QC',
            'heading' => 'Pemeriksaan QC',
            'unit' => $unit,
        ]);
    }

    public function store(int $unitId)
    {
        $rules = [
            'physical_condition' => [
                'label' => 'Kondisi fisik',
                'rules' => 'required',
            ],
            'completeness' => [
                'label' => 'Kelengkapan',
                'rules' => 'required',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $physicalCondition = trim(
            (string) $this->request->getPost('physical_condition')
        );

        $completeness = trim(
            (string) $this->request->getPost('completeness')
        );

        if ($physicalCondition === '' || $completeness === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Kondisi fisik dan kelengkapan wajib diisi.');
        }

        try {
            $inspectionId = $this->qcService->createInspection(
                $unitId,
                (int) session()->get('user_id'),
                $physicalCondition,
                $completeness
            );

            return redirect()
                ->to('/qc/inspections/' . $inspectionId . '/checklist')
                ->with(
                    'success',
                    'Data pemeriksaan berhasil disimpan. Silakan isi checklist QC.'
                );
        } catch (RuntimeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (Throwable $e) {
            log_message(
                'error',
                'Gagal membuat pemeriksaan QC: ' . $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Pemeriksaan QC gagal disimpan. Silakan coba kembali.');
        }
    }

    public function checklist(int $inspectionId)
    {
        $inspection = $this->qcService->getInspection($inspectionId);

        if (! $inspection) {
            return redirect()
                ->to('/qc/inspections')
                ->with('error', 'Data pemeriksaan tidak ditemukan.');
        }

        return view('qc/inspections/checklist', [
            'title'      => 'Checklist QC',
            'heading'    => 'Checklist QC',
            'inspection' => $inspection,
        ]);
    }

    public function saveChecklist(int $inspectionId)
    {
        $inspection = $this->qcService->getInspection($inspectionId);

        if (! $inspection) {
            return redirect()
                ->to('/qc/inspections')
                ->with('error', 'Data pemeriksaan tidak ditemukan.');
        }

        $items = $this->request->getPost('checklist');

        $expectedKeys = [1, 2, 3, 4, 5, 6, 7];

        if (
            ! is_array($items)
            || count($items) !== 7
            || array_diff($expectedKeys, array_map('intval', array_keys($items))) !== []
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Semua 7 checklist wajib diisi.');
        }

        foreach ($expectedKeys as $key) {
            if (! isset($items[$key]) || ! in_array($items[$key], ['1', '0'], true)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Semua 7 checklist wajib diisi.');
            }
        }

        try {
            $this->qcService->saveChecklist(
                $inspectionId,
                $items,
                $inspection['serial_number']
            );

            return redirect()
                ->to('/qc/inspections/' . $inspectionId . '/documentation')
                ->with('success', 'Checklist QC berhasil disimpan. Silahkan upload dokumentasi QC.');
        } catch (RuntimeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (Throwable $e) {
            log_message(
                'error',
                'Gagal menyimpan checklist QC: ' . $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Checklist QC gagal disimpan. Silakan coba kembali.');
        }
    }

    public function documentation(int $inspectionId)
    {
        $inspection = $this->qcService->getInspection($inspectionId);

        if (! $inspection) {
            return redirect()
                ->to('/qc/inspections')
                ->with('error', 'Data pemeriksaan tidak ditemukan.');
        }

        $documents = $this->qcService->getDocuments($inspectionId);

        return view('qc/inspections/documentation', [
            'title'       => 'Dokumentasi QC',
            'heading'     => 'Dokumentasi QC',
            'inspection'  => $inspection,
            'documents'   => $documents,
        ]);
    }

    public function saveDocumentation(int $inspectionId)
    {
        $inspection = $this->qcService->getInspection($inspectionId);

        if (! $inspection) {
            return redirect()
                ->to('/qc/inspections')
                ->with('error', 'Data pemeriksaan tidak ditemukan.');
        }

        $files = [
            'photo_product' => [
                'file' => $this->request->getFile('photo_product'),
                'type' => 'Foto Produk',
                'rules' => 'uploaded[photo_product]'
                    . '|is_image[photo_product]'
                    . '|mime_in[photo_product,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[photo_product,5120]',
                'required' => true,
            ],
            'photo_serial' => [
                'file' => $this->request->getFile('photo_serial'),
                'type' => 'Foto Nomor Seri',
                'rules' => 'uploaded[photo_serial]'
                    . '|is_image[photo_serial]'
                    . '|mime_in[photo_serial,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[photo_serial,5120]',
                'required' => true,
            ],
            'video_unboxing' => [
                'file' => $this->request->getFile('video_unboxing'),
                'type' => 'Video Unboxing',
                'rules' => 'permit_empty'
                    . '|mime_in[video_unboxing,video/mp4,video/webm,video/quicktime]'
                    . '|max_size[video_unboxing,51200]',
                'required' => false,
            ],
        ];

        $validationRules = [];

        foreach ($files as $field => $fileData) {
            $validationRules[$field] = [
                'label' => $fileData['type'],
                'rules' => $fileData['rules'],
            ];
        }

        if (! $this->validate($validationRules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $this->qcService->saveDocumentation(
                $inspectionId,
                $files,
                $inspection['serial_number']
            );

            return redirect()
                ->to('/qc/inspections/' . $inspectionId . '/result')
                ->with('success', 'Dokumentasi QC berhasil disimpan.');
        } catch (RuntimeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (Throwable $e) {
            log_message(
                'error',
                'Gagal menyimpan dokumentasi QC: ' . $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Dokumentasi gagal disimpan. Silakan coba kembali.');
        }
    }

    public function result(int $inspectionId)
    {
        $inspection = $this->qcService->getInspection($inspectionId);

        if (! $inspection) {
            return redirect()
                ->to('/qc/inspections')
                ->with('error', 'Data pemeriksaan tidak ditemukan.');
        }

        $checklist = $this->qcService->getChecklist($inspectionId);

        if (count($checklist) !== 7) {
            return redirect()
                ->to('/qc/inspections/' . $inspectionId . '/checklist')
                ->with('error', 'Checklist QC belum lengkap.');
        }

        if (! $this->qcService->hasCompleteDocuments($inspectionId)) {
            return redirect()
                ->to('/qc/inspections/' . $inspectionId . '/documentation')
                ->with('error', 'Foto produk, foto nomor seri, dan video unboxing wajib diupload.');
        }

        return view('qc/inspections/result', [
            'title'              => 'Hasil QC',
            'heading'            => 'Hasil QC',
            'inspection'         => $inspection,
            'checklist'          => $checklist,
            'hasFailedChecklist' => $this->qcService->hasFailedChecklist($checklist),
        ]);
    }

    public function saveResult(int $inspectionId)
    {
        $inspection = $this->qcService->getInspection($inspectionId);

        if (! $inspection) {
            return redirect()
                ->to('/qc/inspections')
                ->with('error', 'Data pemeriksaan tidak ditemukan.');
        }

        $checklist = $this->qcService->getChecklist($inspectionId);

        if (count($checklist) !== 7) {
            return redirect()
                ->to('/qc/inspections/' . $inspectionId . '/checklist')
                ->with('error', 'Checklist QC belum lengkap.');
        }

        if (! $this->qcService->hasCompleteDocuments($inspectionId)) {
            return redirect()
                ->to('/qc/inspections/' . $inspectionId . '/documentation')
                ->with('error', 'Dokumentasi QC belum lengkap.');
        }

        $result = $this->request->getPost('result');

        $allowedResults = [
            'Lolos QC',
            'Tidak Lolos QC',
            'Perlu Pemeriksaan Ulang',
        ];

        if (! in_array($result, $allowedResults, true)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Hasil QC tidak valid.');
        }

        try {
            $this->qcService->saveResult(
                $inspectionId,
                $inspection,
                $result,
                $this->request->getPost('notes'),
                $checklist
            );

            return redirect()
                ->to('/qc/inspections')
                ->with('success', 'Hasil QC berhasil disimpan.');
        } catch (RuntimeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (Throwable $e) {
            log_message(
                'error',
                'Gagal menyimpan hasil QC: ' . $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Hasil QC gagal disimpan. Silakan coba kembali.');
        }
    }
}
