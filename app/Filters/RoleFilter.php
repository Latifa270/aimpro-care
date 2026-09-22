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

        if (! $userRole || empty($arguments)) {
            return redirect()->to('/login');
        }

        if (! in_array($userRole, $arguments, true)) {
            return redirect()->to('/login');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}
