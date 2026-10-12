<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    private const ALLOWED_ROLES = ['Admin', 'QC'];
    private const MAX_ATTEMPTS = 5;
    private const DECAY_SECONDS = 60;


    public function login()
    {
        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');

            if ($role === 'Admin') {
                return redirect()->to('/admin/dashboard');
            }

            if ($role === 'QC') {
                return redirect()->to('/qc/dashboard');
            }

            session()->destroy();
        }

        return view('auth/login');
    }

    public function authenticate()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors());
        }

        $throttler = service('throttler');
        $ipAddress = $this->request->getIPAddress();
        $rateKey = 'login_' . md5($ipAddress);

        if ($throttler->check($rateKey, self::MAX_ATTEMPTS, self::DECAY_SECONDS) === false) {
            log_message(
                'warning',
                'Login rate limit exceeded. IP: ' . $ipAddress
            );

            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Terlalu banyak percobaan login. Silakan coba lagi dalam '
                        . self::DECAY_SECONDS . ' detik.'
                );
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');
        $userModel = new UserModel();
        $user = $userModel
            ->where('email', $email)
            ->first();

        if (
            ! $user ||
            ! password_verify($password, $user['password'])
        ) {
            return redirect()->back()
                ->with('error', 'Email atau password salah.');
        }

        if (! in_array($user['role'], self::ALLOWED_ROLES, true)) {
            log_message(
                'warning',
                'Login ditolak karena role tidak valid. User ID: ' . $user['id']
            );

            return redirect()->to('/login')
                ->with('error', 'Akun tidak memiliki hak akses aplikasi.');
        }

        $throttler->remove($rateKey);
        session()->regenerate(true);
        session()->set([
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        if ($user['role'] === 'Admin') {
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/qc/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Anda berhasil logout.');
    }
}
