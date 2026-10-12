<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SalesModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class SalesController extends BaseController
{
    protected SalesModel $salesModel;

    public function __construct()
    {
        $this->salesModel = new SalesModel();
    }

    public function index()
    {
        $keyword = trim((string) $this->request->getGet('q'));

        $builder = $this->salesModel
            ->select([
                'sales.id AS sales_id',
                'sales.purchase_date',
                'sales.invoice_number',
                'sales.marketplace',
                'sales.marketplace_order_number',
                'sales.created_at AS transaction_created_at',

                'customers.id AS customer_id',
                'customers.name AS customer_name',
                'customers.whatsapp AS customer_whatsapp',

                'product_units.id AS product_unit_id',
                'product_units.serial_number',
                'product_units.status AS unit_status',

                'products.id AS product_id',
                'products.name AS product_name',
                'products.brand',
                'products.model',

                'warranties.id AS warranty_id',
                'warranties.warranty_number',
                'warranties.start_date AS warranty_start_date',
                'warranties.end_date AS warranty_end_date',
                'warranties.status AS warranty_status',
            ])
            ->join(
                'customers',
                'customers.id = sales.customer_id',
                'left'
            )
            ->join(
                'product_units',
                'product_units.id = sales.product_unit_id',
                'left'
            )
            ->join(
                'products',
                'products.id = product_units.product_id',
                'left'
            )
            ->join(
                'warranties',
                'warranties.sales_id = sales.id',
                'left'
            );

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('customers.name', $keyword)
                ->orLike('customers.whatsapp', $keyword)
                ->orLike('sales.invoice_number', $keyword)
                ->orLike('product_units.serial_number', $keyword)
                ->orLike('warranties.warranty_number', $keyword)
                ->groupEnd();
        }

        $sales = $builder
            ->orderBy('sales.created_at', 'DESC')
            ->paginate(15, 'sales');

        return view('admin/sales/index', [
            'title' => 'Riwayat Penjualan',
            'sales' => $sales,
            'pager' => $this->salesModel->pager,
            'keyword' => $keyword,
        ]);
    }

    public function show(int $id)
    {
        $sale = $this->salesModel
            ->select([
                'sales.*',

                'customers.name AS customer_name',
                'customers.whatsapp AS customer_whatsapp',
                'customers.email AS customer_email',
                'customers.address AS customer_address',

                'product_units.serial_number',
                'product_units.status AS unit_status',

                'products.name AS product_name',
                'products.brand',
                'products.model',

                'warranties.id AS warranty_id',
                'warranties.warranty_number',
                'warranties.start_date AS warranty_start_date',
                'warranties.end_date AS warranty_end_date',
                'warranties.duration_months',
                'warranties.qr_token',
                'warranties.card_path',
                'warranties.status AS warranty_status',
            ])
            ->join(
                'customers',
                'customers.id = sales.customer_id',
                'left'
            )
            ->join(
                'product_units',
                'product_units.id = sales.product_unit_id',
                'left'
            )
            ->join(
                'products',
                'products.id = product_units.product_id',
                'left'
            )
            ->join(
                'warranties',
                'warranties.sales_id = sales.id',
                'left'
            )
            ->where('sales.id', $id)
            ->first();

        if (!$sale) {
            throw PageNotFoundException::forPageNotFound(
                'Data penjualan tidak ditemukan.'
            );
        }

        return view('admin/sales/show', [
            'title' => 'Detail Penjualan',
            'sale' => $sale,
        ]);
    }
}
