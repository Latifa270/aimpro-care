<?php

namespace App\Validation;

class CustomerRules
{
    public static function rules(): array
    {
        return [
            'name' => [
                'label' => 'Nama pembeli',
                'rules' => 'required|string|max_length[50]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'string' => '{field} harus berupa teks.',
                    'max_length' => '{field} maksimal 50 karakter.',
                ],
            ],

            'whatsapp' => [
                'label' => 'Nomor WhatsApp',
                'rules' => 'required|string|max_length[20]|regex_match[/^[0-9+\-\s()]{8,20}$/]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'string' => '{field} harus berupa teks.',
                    'max_length' => '{field} maksimal 20 karakter.',
                    'regex_match' => '{field} hanya boleh berisi angka dan karakter nomor telepon yang valid.',
                ],
            ],

            'email' => [
                'label' => 'Email',
                'rules' => 'permit_empty|valid_email|max_length[100]',
                'errors' => [
                    'valid_email' => 'Format {field} tidak valid.',
                    'max_length' => '{field} maksimal 100 karakter.',
                ],
            ],

            'address' => [
                'label' => 'Alamat',
                'rules' => 'required|string',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'string' => '{field} harus berupa teks.',
                ],
            ],
        ];
    }
}
