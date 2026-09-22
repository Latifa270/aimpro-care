<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWarrantiesTable extends Migration
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
            'sale_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'warranty_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
            ],
            'qr_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'card_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Garansi Aktif',
                    'Garansi Berakhir',
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

        $this->forge->addUniqueKey(
            ['sale_id'],
            'warranties_sale_unique'
        );

        $this->forge->addUniqueKey(
            ['warranty_number'],
            'warranties_number_unique'
        );

        $this->forge->addUniqueKey(
            ['qr_token'],
            'warranties_qr_token_unique'
        );

        $this->forge->addKey('end_date');
        $this->forge->addKey('status');

        $this->forge->addForeignKey(
            'sale_id',
            'sales',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->createTable('warranties');
    }

    public function down()
    {
        $this->forge->dropTable('warranties', true);
    }
}
