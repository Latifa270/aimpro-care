<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Product extends BaseController
{

    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Produk',
            'heading'  => 'Produk',
            'products' => $this->productModel
                ->orderBy('created_at', 'DESC')
                ->findAll(),
        ];

        return view('admin/products/index', $data);
    }

    public function create()
    {
        $data = [
            'title'   => 'Tambah Produk',
            'heading' => 'Tambah Produk',
        ];

        return view('admin/products/create', $data);
    }

    public function store()
    {
        $rules = [
            'name' => [
                'label'  => 'Nama produk',
                'rules'  => 'required|max_length[150]',
            ],
            'brand' => [
                'label'  => 'Brand',
                'rules'  => 'required|max_length[100]',
            ],
            'model' => [
                'label'  => 'Model',
                'rules'  => 'required|max_length[100]',
            ],
            'description' => [
                'label'  => 'Deskripsi',
                'rules'  => 'permit_empty',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->productModel->insert([
            'name'        => trim($this->request->getPost('name')),
            'brand'       => trim($this->request->getPost('brand')),
            'model'       => trim($this->request->getPost('model')),
            'description' => trim($this->request->getPost('description')),
        ]);

        return redirect()
            ->to('/admin/products')
            ->with('success', 'Produk berhasil ditambahkan.');
    }
}
