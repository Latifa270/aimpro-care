<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesTable extends Migration
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
            'customer_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'purchase_date' => [
                'type' => 'DATE',
            ],
            'invoice_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'marketplace' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'marketplace_order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'receipt_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'sales_note' => [
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

        $this->forge->addUniqueKey(
            ['product_unit_id'],
            'sales_product_unit_unique'
        );

        $this->forge->addKey('invoice_number');

        $this->forge->addForeignKey(
            'product_unit_id',
            'product_units',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->addForeignKey(
            'customer_id',
            'customers',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->createTable('sales');
    }

    public function down()
    {
        $this->forge->dropTable('sales', true);
    }
}
