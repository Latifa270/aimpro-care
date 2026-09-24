<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            if (session()->get('role') === 'Admin') {
                return redirect()->to('/admin/dashboard');
            }

            if (session()->get('role') === 'Qc') {
                return redirect()->to('/qc/dashboard');
            }
        }

        return view('auth/login');
    }

    public function authenticate()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel
            ->where('email', $this->request->getPost('email'))
            ->first();

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah');
        }

        session()->regenerate();

        session()->set([
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true
        ]);

        if ($user['role'] === 'Admin') {
            return redirect()->to('/admin/dashboard');
        }

        if ($user['role'] === 'QC') {
            return redirect()->to('/qc/dashboard');
        }

        return redirect()->to('/login')
            ->with('error', 'Role tidak valid');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
