<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userRole = session()->get('role');

        if (empty($userRole)) {
            return redirect()
                ->to('/login')
                ->with('error', 'Sesi pengguna tidak valid. Silahkan login kembali.');
        }

        if (empty($arguments)) {
            log_message('error', 'RoleFilter dipanggil tanpa argumen role yang diizinkan. ');

            return service('response')
                ->setStatusCode(403)
                ->setBody('Akses ditolak. ');
        }

        if (! in_array($userRole, $arguments, true)) {
            log_message(
                'warning',
                'Akses ditolak untuk role: ' . $userRole
            );

            return service('response')
                ->setStatusCode(403)
                ->setBody('Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
        return null;
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}
