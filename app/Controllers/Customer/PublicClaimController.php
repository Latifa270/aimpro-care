<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Services\ClaimSubmissionService;
use App\Validation\ClaimRules;
use Throwable;

class PublicClaimController extends BaseController
{
    public function create(string $token)
    {
        if (! preg_match('/^[a-f0-9]{64}$/i', $token)) {
            return redirect()->to('/cek-garansi')
                ->with('error', 'Tautan klaim tidak valid.');
        }

        return view('public/claims/create', [
            'token' => $token,
        ]);
    }

    public function store(string $token)
    {
        if (! preg_match('/^[a-f0-9]{64}$/i', $token)) {
            return redirect()->to('/cek-garansi')
                ->with('error', 'Tautan klaim tidak valid.');
        }

        $rules = ClaimRules::rules();

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $photo = $this->request->getFile('damage_photo');
        $video = $this->request->getFile('damage_video');

        if (! $photo || ! $photo->isValid()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Foto kerusakan wajib diunggah.');
        }

        try {
            $service = new ClaimSubmissionService();

            $claimId = $service->submit(
                $token,
                $this->request->getPost(),
                $photo,
                $video
            );

            session()->setFlashdata('claim_success_id', $claimId);

            return redirect()->to('/klaim/berhasil');
        } catch (Throwable $e) {
            log_message(
                'error',
                'Claim submission failed: {message}',
                ['message' => $e->getMessage()]
            );

            $userMessage = $e instanceof \RuntimeException
                ? $e->getMessage()
                : 'Pengajuan klaim gagal diproses. Silakan coba kembali.';

            return redirect()->back()
                ->withInput()
                ->with('error', $userMessage);
        }
    }

    public function success()
    {
        $claimId = session()->getFlashdata('claim_success_id');

        if (! $claimId) {
            return redirect()
                ->to('/cek-garansi')
                ->with('error', 'Tidak ada pengajuan klaim yang berhasil ditemukan.');
        }

        return view('public/claims/success', [
            'claimId' => $claimId,
        ]);
    }
}
