<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key'   => 'company_name',
                'value' => 'AIMpro Care',
            ],
            [
                'key'   => 'company_address',
                'value' => '',
            ],
            [
                'key'   => 'company_phone',
                'value' => '',
            ],
            [
                'key'   => 'max_file_size',
                'value' => '5120',
            ],
            [
                'key'   => 'warranty_reminder_days',
                'value' => '30',
            ],
        ];

        foreach ($settings as $setting) {
            $existing = $this->db
                ->table('settings')
                ->where('key', $setting['key'])
                ->get()
                ->getRow();

            if ($existing) {
                continue;
            }

            $this->db->table('settings')->insert([
                'key'        => $setting['key'],
                'value'      => $setting['value'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
