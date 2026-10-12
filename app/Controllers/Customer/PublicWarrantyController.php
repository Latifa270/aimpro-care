<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Services\PublicWarrantyLookupService;
use Throwable;

class PublicWarrantyController extends BaseController
{
    private PublicWarrantyLookupService $lookupService;
    private const MAX_ATTEMPTS = 10;
    private const DECAY_SECONDS = 60;

    public function __construct()
    {
        $this->lookupService = new PublicWarrantyLookupService();
    }

    public function index()
    {
        return view('public/warranty_lookup/index', [
            'result' => null,
            'keyword' => '',
            'notFound' => false,
        ]);
    }

    public function search()
    {
        $throttler = service('throttler');
        $ipAddress = $this->request->getIPAddress();
        $rateKey = 'warranty_lookup_' . md5($ipAddress);

        if ($throttler->check($rateKey, self::MAX_ATTEMPTS, self::DECAY_SECONDS) === false) {
            log_message(
                'warning',
                'Warranty lookup rate limit exceeded. IP: ' . $ipAddress
            );

            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Terlalu banyak pencarian. Silakan coba lagi dalam '
                        . self::DECAY_SECONDS . ' detik.'
                );
        }

        $keyword = trim((string) $this->request->getPost('keyword'));

        if ($keyword === '' || strlen($keyword) > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Masukkan nomor garansi atau nomor seri yang valid.');
        }

        try {
            $result = $this->lookupService->lookup($keyword);

            return view('public/warranty_lookup/index', [
                'result' => $result,
                'keyword' => $keyword,
                'notFound' => $result === null,
            ]);
        } catch (Throwable $e) {
            log_message(
                'error',
                'Public warranty lookup error: {message}',
                ['message' => $e->getMessage()]
            );

            return view('public/warranty_lookup/index', [
                'result' => null,
                'keyword' => $keyword,
                'notFound' => false,
                'error' => 'Terjadi kesalahan saat memeriksa garansi. Silakan coba kembali.',
            ]);
        }
    }

    public function byToken(string $token)
    {
        if (! preg_match('/^[a-f0-9]{64}$/i', $token)) {
            return view('public/warranty_lookup/index', [
                'result' => null,
                'keyword' => '',
                'notFound' => true,
            ]);
        }

        try {
            $result = $this->lookupService->lookup($token);

            return view('public/warranty_lookup/index', [
                'result' => $result,
                'keyword' => '',
                'notFound' => $result === null,
            ]);
        } catch (Throwable $e) {
            log_message(
                'error',
                'Public warranty token lookup error: {message}',
                ['message' => $e->getMessage()]
            );

            return view('public/warranty_lookup/index', [
                'result' => null,
                'keyword' => '',
                'notFound' => false,
                'error' => 'Terjadi kesalahan saat memeriksa garansi. Silakan coba kembali.',
            ]);
        }
    }
}
