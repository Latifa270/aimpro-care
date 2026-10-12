<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeClaimHistoryUserIdNullable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('claim_histories', [
            'user_id' => [
                'name'       => 'user_id',
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('claim_histories', [
            'user_id' => [
                'name'       => 'user_id',
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
    }
}
