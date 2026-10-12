<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WarrantyClaimModel;
use App\Services\ClaimProcessingService;
use DomainException;

class ClaimProcessingController extends BaseController
{
    protected ClaimProcessingService $claimService;
    protected WarrantyClaimModel $claimModel;

    public function __construct()
    {
        $this->claimService = new ClaimProcessingService();
        $this->claimModel = new WarrantyClaimModel();
    }

    public function index()
    {
        $claims = $this->claimModel->getClaimWithRelations();

        return view('admin/claims/index', [
            'claims' => $claims,
        ]);
    }

    public function show(int $id)
    {
        $claim = $this->claimModel->getClaimWithRelations($id);

        if (! $claim) {
            return redirect()->to('/admin/claims')
                ->with('error', 'Data klaim tidak ditemukan.');
        }

        return view('admin/claims/show', [
            'claim' => $claim,
            'history' => $this->claimService->getHistory($id),
            'availableTransitions' =>
            $this->claimService->getAvailableTransitions(
                $claim['status']
            ),
        ]);
    }

    public function updateStatus(int $id)
    {
        $rules = [
            'status' => [
                'label' => 'Status klaim',
                'rules' => 'required|in_list[Pengajuan Baru,Sedang Diverifikasi,Klaim Diterima,Klaim Ditolak,Barang Diterima,Sedang Diperiksa,Sedang Diproses,Selesai]',
            ],
            'note' => [
                'label' => 'Catatan',
                'rules' => 'permit_empty|max_length[5000]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $adminId = (int) session()->get('user_id');

        if ($adminId <= 0) {
            return redirect()->back()
                ->with('error', 'Sesi admin tidak valid.');
        }

        try {
            $result = $this->claimService->changeStatus(
                $id,
                $adminId,
                (string) $this->request->getPost('status'),
                $this->request->getPost('note')
            );

            return redirect()->to('/admin/claims/' . $id)
                ->with(
                    'success',
                    'Status klaim berhasil diperbarui.'
                );
        } catch (DomainException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message(
                'error',
                'Claim processing failed: ' . $e->getMessage()
            );

            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memproses klaim.'
                );
        }
    }
}
