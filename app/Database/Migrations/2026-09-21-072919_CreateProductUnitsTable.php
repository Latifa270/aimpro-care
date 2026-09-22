<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductUnitsTable extends Migration
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
            'product_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'serial_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Draft',
                    'Menunggu QC',
                    'Perlu Pemeriksaan Ulang',
                    'Tidak Lolos QC',
                    'Siap Dijual',
                    'Terjual',
                    'Dikembalikan',
                ],
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addUniqueKey(
            ['serial_number'],
            'product_units_serial_number_unique'
        );

        $this->forge->addKey('status');

        $this->forge->addForeignKey(
            'product_id',
            'products',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->createTable('product_units');
    }

    public function down()
    {
        $this->forge->dropTable('product_units', true);
    }
}
