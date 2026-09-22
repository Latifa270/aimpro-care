<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQcDocumentationsTable extends Migration
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
            'document_type' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Video Unboxing',
                    'Foto Nomor Seri',
                    'Foto Produk',
                ],
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'file_size' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
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

        $this->forge->createTable('qc_documentations');
    }

    public function down()
    {
        $this->forge->dropTable('qc_documentations', true);
    }
}
