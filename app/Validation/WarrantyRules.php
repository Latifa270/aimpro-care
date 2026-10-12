<?php

namespace App\Validation;

class WarrantyRules
{
    public static function rules(): array
    {
        return [
            'duration_months' => [
                'label' => 'Durasi garansi',
                'rules' => 'required|integer|greater_than[0]|less_than_equal_to[120]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'integer' => '{field} harus berupa angka bulat.',
                    'greater_than' => '{field} harus lebih dari 0 bulan.',
                    'less_than_equal_to' => '{field} maksimal 120 bulan.',
                ],
            ],
        ];
    }
}
