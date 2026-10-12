<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\SettingService;
use Throwable;

class SettingController extends BaseController
{
    protected SettingService $settingService;

    public function __construct()
    {
        $this->settingService = new SettingService();
    }

    public function index()
    {
        $settings = $this->settingService->getAll();

        return view('admin/settings/index', [
            'title'    => 'Pengaturan',
            'heading'  => 'Pengaturan',
            'settings' => $settings,
        ]);
    }

    public function update()
    {
        $rules = [
            'company_name'       => 'permit_empty|max_length[100]',
            'company_address'    => 'permit_empty|max_length[500]',
            'company_phone'      => 'permit_empty|max_length[20]',
            'max_file_size'      => 'required|is_natural_no_zero|less_than_equal_to[51200]',
            'warranty_reminder_days' => 'required|is_natural_no_zero|less_than_equal_to[90]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $this->settingService->updateAll([
                'company_name'           => trim((string) $this->request->getPost('company_name')),
                'company_address'        => trim((string) $this->request->getPost('company_address')),
                'company_phone'          => trim((string) $this->request->getPost('company_phone')),
                'max_file_size'          => (int) $this->request->getPost('max_file_size'),
                'warranty_reminder_days' => (int) $this->request->getPost('warranty_reminder_days'),
            ]);

            return redirect()
                ->to('/admin/settings')
                ->with('success', 'Pengaturan berhasil disimpan.');
        } catch (Throwable $e) {
            log_message(
                'error',
                'Gagal menyimpan pengaturan: ' . $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Pengaturan gagal disimpan. Silakan coba kembali.');
        }
    }
}
