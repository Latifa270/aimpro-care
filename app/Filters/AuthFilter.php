<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('isLoggedIn')) {
            return redirect()
                ->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = $session->get('user_id');

        if (empty($userId)) {
            $session->destroy();

            return redirect()
                ->to('/login')
                ->with('error', 'Sesi pengguna tidak valid. Silahkan login kembali.');
        }

        $user = (new UserModel())->find($userId);

        if (! $user) {
            $session->destroy();

            return redirect()
                ->to('/login')
                ->with('error', 'Akun tidak ditemukan atau telah dihapus. Silahkan login kembali.');
        }

        if (
            $user['role'] !== $session->get('role') ||
            ! in_array($user['role'], ['Admin', 'QC'], true)
        ) {
            $session->destroy();

            return redirect()
                ->to('/login')
                ->with('error', 'Hak akses berubah. Silakan login kembali.');
        }

        return null;
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // Tidak ada proses setelah request.
    }
}
