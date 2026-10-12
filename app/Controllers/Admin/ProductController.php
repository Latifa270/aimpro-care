<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Libraries\ActivityLogger;

class ProductController extends BaseController
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

    public function edit(int $id)
    {
        $product = $this->productModel->find($id);

        if (! $product) {
            return redirect()
                ->to('/admin/products')
                ->with('error', 'Produk tidak ditemukan.');
        }

        $data = [
            'title'   => 'Edit Produk',
            'heading' => 'Edit Produk',
            'product' => $product,
        ];

        return view('admin/products/edit', $data);
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

        $productData = [
            'name'        => trim($this->request->getPost('name')),
            'brand'       => trim($this->request->getPost('brand')),
            'model'       => trim($this->request->getPost('model')),
            'description' => trim($this->request->getPost('description')),
        ];

        $productId = $this->productModel->insert($productData);

        if ($productId) {
            ActivityLogger::log(
                'CREATE_PRODUCT',
                'Menambahkan produk '
                    . $productData['name']
                    . ' ('
                    . $productData['brand']
                    . ' '
                    . $productData['model']
                    . ').'
            );
        }

        return redirect()
            ->to('/admin/products')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        $product = $this->productModel->find($id);

        if (! $product) {
            return redirect()
                ->to('/admin/products')
                ->with('error', 'Produk tidak ditemukan.');
        }

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

        $productData = [
            'name'        => trim($this->request->getPost('name')),
            'brand'       => trim($this->request->getPost('brand')),
            'model'       => trim($this->request->getPost('model')),
            'description' => trim($this->request->getPost('description')),
        ];

        $updated = $this->productModel->update($id, $productData);

        if ($updated) {
            ActivityLogger::log(
                'UPDATE_PRODUCT',
                'Memperbarui data produk '
                    . $productData['name']
                    . ' ('
                    . $productData['brand']
                    . ' '
                    . $productData['model']
                    . ').'
            );
        }

        return redirect()
            ->to('/admin/products')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $product = $this->productModel->find($id);

        if (! $product) {
            return redirect()
                ->to('/admin/products')
                ->with('error', 'Produk tidak ditemukan.');
        }
        $deleted = $this->productModel->delete($id);

        if ($deleted) {
            ActivityLogger::log(
                'DELETE_PRODUCT',
                'Menghapus produk '
                    . $product['name']
                    . ' ('
                    . $product['brand']
                    . ' '
                    . $product['model']
                    . ').'
            );
        }

        return redirect()
            ->to('/admin/products')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
