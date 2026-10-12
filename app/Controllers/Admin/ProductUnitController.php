<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ProductUnitModel;
use App\Libraries\ActivityLogger;

class ProductUnitController extends BaseController
{
    protected ProductUnitModel $productUnitModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productUnitModel = new ProductUnitModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $units = $this->productUnitModel
            ->select('product_units.*, products.name AS product_name, products.brand, products.model')
            ->join('products', 'products.id = product_units.product_id')
            ->orderBy('product_units.created_at', 'DESC')
            ->findAll();

        $products = $this->productModel
            ->orderBy('name', 'ASC')
            ->findAll();

        $data = [
            'title'   => 'Unit',
            'heading' => 'Unit',
            'units'   => $units,
            'products' => $products,
        ];

        return view('admin/units/index', $data);
    }

    public function store()
    {
        $rules = [
            'product_id' => [
                'label' => 'Produk',
                'rules' => 'required|is_natural_no_zero',
            ],
            'serial_number' => [
                'label' => 'Serial Number',
                'rules' => 'required|max_length[100]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $product = $this->productModel->find(
            $this->request->getPost('product_id')
        );

        if (! $product) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Produk tidak ditemukan.');
        }

        $serialNumber = trim(
            $this->request->getPost('serial_number')
        );

        $existingUnit = $this->productUnitModel
            ->where('serial_number', $serialNumber)
            ->first();

        if ($existingUnit) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Serial number sudah terdaftar.');
        }

        $unitId = $this->productUnitModel->insert([
            'product_id'    => $product['id'],
            'serial_number' => $serialNumber,
            'status'        => 'Menunggu QC',
        ]);


        if ($unitId) {
            ActivityLogger::log(
                'CREATE_UNIT',
                'Menambahkan unit dengan serial number '
                    . $serialNumber
                    . ' untuk produk '
                    . $product['name']
                    . '.'
            );
        }

        return redirect()
            ->to('/admin/units')
            ->with('success', 'Unit berhasil ditambahkan.');
    }

    public function readyForSale()
    {
        $units = $this->productUnitModel
            ->select('product_units.*, products.name AS product_name, products.brand, products.model')
            ->join('products', 'products.id = product_units.product_id')
            ->where('product_units.status', 'Siap Dijual')
            ->orderBy('product_units.updated_at', 'DESC')
            ->findAll();

        $data = [
            'title'   => 'Daftar Siap Jual',
            'heading' => 'Daftar Siap Jual',
            'units'   => $units,
        ];

        return view('admin/units/ready_for_sale', $data);
    }

    public function delete(int $id)
    {
        $unit = $this->productUnitModel->find($id);

        if (! $unit) {
            return redirect()
                ->to('/admin/units')
                ->with('error', 'Unit tidak ditemukan.');
        }

        $hasInspection = (new \App\Models\QcInspectionModel())
            ->where('product_unit_id', $id)
            ->countAllResults();

        if ($hasInspection > 0) {
            return redirect()
                ->to('/admin/units')
                ->with('error', 'Unit tidak dapat dihapus karena sudah memiliki data QC.');
        }

        $deleted = $this->productUnitModel->delete($id);

        if ($deleted) {
            ActivityLogger::log(
                'DELETE_UNIT',
                'Menghapus unit dengan serial number '
                    . $unit['serial_number']
                    . '.'
            );
        }

        return redirect()
            ->to('/admin/units')
            ->with('success', 'Unit berhasil dihapus.');
    }

    public function resendToQc(int $id)
    {
        $unit = $this->productUnitModel->find($id);

        if (! $unit) {
            return redirect()
                ->to('/admin/units')
                ->with('error', 'Unit tidak ditemukan.');
        }

        if ($unit['status'] !== 'Perlu Pemeriksaan Ulang') {
            return redirect()
                ->to('/admin/units')
                ->with('error', 'Unit ini tidak dalam status Perlu Pemeriksaan Ulang.');
        }

        $updated = $this->productUnitModel->update($id, [
            'status' => 'Menunggu QC',
        ]);

        if ($updated) {
            ActivityLogger::log(
                'RESEND_TO_QC',
                'Mengirim ulang unit dengan serial number '
                    . $unit['serial_number']
                    . ' ke QC.'
            );
        }

        return redirect()
            ->to('/admin/units')
            ->with('success', 'Unit berhasil dikirim ulang ke QC.');
    }
}
