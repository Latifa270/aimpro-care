<?php

namespace App\Validation;

class ClaimRules
{
    public static function rules(): array
    {
        return [
            'warranty_id' => [
                'label' => 'Garansi',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'integer' => '{field} harus berupa angka bulat.',
                    'greater_than' => '{field} tidak valid.',
                ],
            ],

            'complaint' => [
                'label' => 'Keluhan',
                'rules' => 'required|string|min_length[10]|max_length[5000]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'string' => '{field} harus berupa teks.',
                    'min_length' => '{field} minimal 10 karakter.',
                    'max_length' => '{field} maksimal 5000 karakter.',
                ],
            ],

            'damage_start_date' => [
                'label' => 'Tanggal mulai kerusakan',
                'rules' => 'required|valid_date[Y-m-d]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'valid_date' => 'Format {field} harus YYYY-MM-DD dan merupakan tanggal yang valid.',
                ],
            ],

            'whatsapp' => [
                'label' => 'Nomor WhatsApp',
                'rules' => 'required|string|max_length[20]|regex_match[/^[0-9+\-\s()]{8,20}$/]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'string' => '{field} harus berupa teks.',
                    'max_length' => '{field} maksimal 20 karakter.',
                    'regex_match' => 'Format {field} tidak valid.',
                ],
            ],

            'handover_method' => [
                'label' => 'Metode penyerahan',
                'rules' => 'required|string|max_length[100]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'string' => '{field} harus berupa teks.',
                    'max_length' => '{field} maksimal 100 karakter.',
                ],
            ],

            'shipping_address' => [
                'label' => 'Alamat pengiriman',
                'rules' => 'permit_empty|string',
                'errors' => [
                    'string' => '{field} harus berupa teks.',
                ],
            ],

            'terms_agreed' => [
                'label' => 'Persetujuan syarat dan ketentuan',
                'rules' => 'required|in_list[1,true]',
                'errors' => [
                    'required' => 'Persetujuan syarat dan ketentuan wajib dicentang.',
                    'in_list' => 'Persetujuan syarat dan ketentuan wajib diberikan.',
                ],
            ],

            'damage_photo' => [
                'label' => 'Foto kerusakan',
                'rules' => 'uploaded[damage_photo]|is_image[damage_photo]|max_size[damage_photo,5120]|mime_in[damage_photo,image/jpg,image/jpeg,image/png,image/webp]',
                'errors' => [
                    'uploaded' => '{field} wajib diunggah.',
                    'is_image' => '{field} harus berupa gambar.',
                    'max_size' => '{field} maksimal 5 MB.',
                    'mime_in' => 'Format {field} harus JPG, JPEG, PNG, atau WebP.',
                ],
            ],

            'damage_video' => [
                'label' => 'Video kerusakan',
                'rules' => 'max_size[damage_video,20480]|mime_in[damage_video,video/mp4,video/quicktime,video/webm]',
                'errors' => [
                    'max_size' => '{field} maksimal 20 MB.',
                    'mime_in' => 'Format {field} harus MP4, MOV, atau WebM.',
                ],
            ],
        ];
    }
}
