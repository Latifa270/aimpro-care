<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWarrantyClaimsTable extends Migration
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
            'warranty_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'complaint' => [
                'type' => 'TEXT',
            ],
            'damage_start_date' => [
                'type' => 'DATE',
            ],
            'damage_photo_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
            ],
            'damage_video_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'whatsapp' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'handover_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'shipping_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'terms_agreed' => [
                'type' => 'BOOLEAN',
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
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addKey('status');

        $this->forge->addForeignKey(
            'warranty_id',
            'warranties',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->createTable('warranty_claims');
    }

    public function down()
    {
        $this->forge->dropTable('warranty_claims', true);
    }
}
