<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'deleted_at');
    }
}
