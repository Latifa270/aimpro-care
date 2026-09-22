<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClaimHistoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'warranty_claim_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Pengajuan Baru',
                    'Sedang Diverifikasi',
                    'Klaim Diterima',
                    'Klaim Ditolak',
                    'Barang Diterima',
                    'Sedang Diperiksa',
                    'Sedang Diproses',
                    'Selesai',
                ],
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('status');

        $this->forge->addForeignKey(
            'warranty_claim_id',
            'warranty_claims',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->createTable('claim_histories');
    }

    public function down()
    {
        $this->forge->dropTable('claim_histories', true);
    }
}
