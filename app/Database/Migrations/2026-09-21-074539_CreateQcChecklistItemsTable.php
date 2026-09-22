<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQcChecklistItemsTable extends Migration
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
            'qc_inspection_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'result' => [
                'type' => 'BOOLEAN',
            ],
            'note' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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

        $this->forge->addForeignKey(
            'qc_inspection_id',
            'qc_inspections',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('qc_checklist_items');
    }

    public function down()
    {
        $this->forge->dropTable('qc_checklist_items', true);
    }
}
