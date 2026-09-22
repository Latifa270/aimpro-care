<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name'  => 'Administrator',
                'email' => 'admin@aimprocare.test',
                'password' => 'Admin123',
                'role'  => 'Admin',
            ],
            [
                'name'  => 'Petugas QC',
                'email' => 'qc@aimprocare.test',
                'password' => 'Qc123',
                'role'  => 'QC',
            ],
        ];

        foreach ($users as $user) {
            $existingUser = $this->db
                ->table('users')
                ->where('email', $user['email'])
                ->get()
                ->getRow();

            if ($existingUser) {
                continue;
            }

            $this->db->table('users')->insert([
                'name'       => $user['name'],
                'email'      => $user['email'],
                'password'   => password_hash($user['password'], PASSWORD_DEFAULT),
                'role'       => $user['role'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
