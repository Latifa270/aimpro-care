<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use Throwable;

class CustomerController extends BaseController
{
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        try {
            $customers = $this->customerModel
                ->orderBy('created_at', 'DESC')
                ->findAll();

            return view('admin/customers/index', [
                'title'     => 'Data Pembeli',
                'heading'   => 'Data Pembeli',
                'customers' => $customers,
            ]);
        } catch (Throwable $e) {
            log_message(
                'error',
                'Gagal mengambil data pembeli: ' . $e->getMessage()
            );

            return redirect()
                ->to('/admin/customers')
                ->with(
                    'error',
                    'Data pembeli tidak dapat dimuat.'
                );
        }
    }
}
