<?php

namespace App\Validation;

class SalesRules
{
    public static function rules(): array
    {
        return [
            'product_unit_id' => [
                'label' => 'Unit produk',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'integer' => '{field} harus berupa angka bulat.',
                    'greater_than' => '{field} tidak valid.',
                ],
            ],

            'customer_id' => [
                'label' => 'Pembeli',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'integer' => '{field} harus berupa angka bulat.',
                    'greater_than' => '{field} tidak valid.',
                ],
            ],

            'purchase_date' => [
                'label' => 'Tanggal pembelian',
                'rules' => 'required|valid_date[Y-m-d]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'valid_date' => 'Format {field} harus YYYY-MM-DD dan merupakan tanggal yang valid.',
                ],
            ],

            'invoice_number' => [
                'label' => 'Nomor invoice',
                'rules' => 'required|string|max_length[100]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'string' => '{field} harus berupa teks.',
                    'max_length' => '{field} maksimal 100 karakter.',
                ],
            ],

            'marketplace' => [
                'label' => 'Marketplace',
                'rules' => 'permit_empty|string|max_length[100]',
                'errors' => [
                    'string' => '{field} harus berupa teks.',
                    'max_length' => '{field} maksimal 100 karakter.',
                ],
            ],

            'marketplace_order_number' => [
                'label' => 'Nomor pesanan marketplace',
                'rules' => 'permit_empty|string|max_length[100]',
                'errors' => [
                    'string' => '{field} harus berupa teks.',
                    'max_length' => '{field} maksimal 100 karakter.',
                ],
            ],

            'sales_note' => [
                'label' => 'Catatan penjualan',
                'rules' => 'permit_empty|string',
                'errors' => [
                    'string' => '{field} harus berupa teks.',
                ],
            ],
        ];
    }

    public static function receiptRules(): array
    {
        return [
            'receipt' => [
                'label' => 'Bukti pembelian',
                'rules' => 'permit_empty|max_size[receipt,5120]',
                'errors' => [
                    'max-size' => '{field} maksimal 5MB.',
                ],
            ],
        ];
    }
}
