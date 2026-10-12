<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWarrantyAndProductDuration extends Migration
{
    public function up()
    {
        $this->db->query(
            'ALTER TABLE `warranties`
             CHANGE COLUMN `sale_id` `sales_id`
             BIGINT(20) UNSIGNED NOT NULL'
        );

        $this->forge->addColumn('products', [
            'warranty_duration_months' => [
                'type'       => 'INT',
                'constraint' => 3,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'description',
            ],
        ]);

        $this->forge->addColumn('warranties', [
            'duration_months' => [
                'type'       => 'INT',
                'constraint' => 3,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'end_date',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn(
            'warranties',
            'duration_months'
        );

        $this->forge->dropColumn(
            'products',
            'warranty_duration_months'
        );

        $this->db->query(
            'ALTER TABLE `warranties`
             CHANGE COLUMN `sales_id` `sale_id`
             BIGINT(20) UNSIGNED NOT NULL'
        );
    }
}
