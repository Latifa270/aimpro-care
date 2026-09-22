<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
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
                'null'       => true,
            ],
            'warranty_claim_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'destination' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'template' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Berhasil',
                    'Gagal',
                ],
            ],
            'gateway_response' => [
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
        $this->forge->addKey('status');

        $this->forge->addForeignKey(
            'warranty_id',
            'warranties',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->addForeignKey(
            'warranty_claim_id',
            'warranty_claims',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->createTable('notifications');
    }

    public function down()
    {
        $this->forge->droptable('notifications', true);
    }
}
