<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\WarrantyCardService;
use Throwable;

class WarrantyCardController extends BaseController
{
    private WarrantyCardService $service;

    public function __construct()
    {
        $this->service = new WarrantyCardService();
    }

    public function index()
    {
        try {
            $cards = $this->service->getDigitalCards();

            return view('admin/warranty_cards/index', [
                'title' => 'Kartu Garansi',
                'cards' => $cards,
            ]);
        } catch (Throwable $e) {
            log_message(
                'error',
                'Gagal mengambil daftar kartu garansi: {message}',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return redirect()
                ->to(site_url('admin/warranty-activation'))
                ->with(
                    'error',
                    'Daftar kartu garansi tidak dapat dimuat.'
                );
        }
    }

    public function show(int $id)
    {
        try {
            $card = $this->service->getDigitalCard($id);

            if (! $card) {
                return redirect()
                    ->to(site_url('admin/warranty-activation'))
                    ->with(
                        'error',
                        'Data kartu garansi tidak ditemukan.'
                    );
            }

            return view('admin/warranty_cards/show', [
                'card' => $card,
            ]);
        } catch (Throwable $e) {
            log_message(
                'error',
                'Gagal menampilkan kartu garansi ID {id}: {message}',
                [
                    'id' => $id,
                    'message' => $e->getMessage(),
                ]
            );

            return redirect()
                ->to(site_url('admin/warranty-activation'))
                ->with(
                    'error',
                    'Kartu garansi tidak dapat ditampilkan.'
                );
        }
    }

    public function generate(int $id)
    {
        try {
            $this->service->generate($id);

            return redirect()
                ->to(site_url('admin/warranty-cards/' . $id))
                ->with(
                    'success',
                    'Kartu garansi berhasil dibuat.'
                );
        } catch (Throwable $e) {
            log_message(
                'error',
                'Gagal membuat kartu garansi ID {id}: {message}',
                [
                    'id' => $id,
                    'message' => $e->getMessage(),
                ]
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Kartu garansi gagal dibuat. Silakan coba kembali.'
                );
        }
    }

    public function download(int $id)
    {
        try {
            $card = $this->service->getDigitalCard($id);

            if (! $card) {
                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Data kartu garansi tidak ditemukan.'
                    );
            }

            $relativePath = $card['card_path'] ?? null;

            if (! $relativePath) {
                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'File PDF kartu garansi belum tersedia.'
                    );
            }

            $absolutePath = WRITEPATH . $relativePath;

            if (! is_file($absolutePath)) {
                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'File PDF kartu garansi tidak ditemukan.'
                    );
            }

            return $this->response
                ->download($absolutePath, null)
                ->setFileName(
                    ($card['warranty_number'] ?? 'warranty-card') . '.pdf'
                );
        } catch (Throwable $e) {
            log_message(
                'error',
                'Gagal download kartu garansi ID {id}: {message}',
                [
                    'id' => $id,
                    'message' => $e->getMessage(),
                ]
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Kartu garansi tidak dapat diunduh. Silahkan coba kembali'
                );
        }
    }
}
