<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQcInspectionsTable extends Migration
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
            'product_unit_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'inspection_date' => [
                'type' => 'DATETIME',
            ],
            'physical_condition' => [
                'type' => 'TEXT',
            ],
            'completeness' => [
                'type' => 'TEXT',
            ],
            'result' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Lolos QC',
                    'Tidak Lolos QC',
                    'Perlu Pemeriksaan Ulang',
                ],
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('result');

        $this->forge->addForeignKey(
            'product_unit_id',
            'product_units',
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

        $this->forge->createTable('qc_inspections');
    }

    public function down()
    {
        $this->forge->dropTable('qc_inspections', true);
    }
}
