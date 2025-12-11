<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLevelColumnToCourses extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('level', 'courses')) {
            $fields = [
                'level' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                    'after'      => 'description',
                ],
            ];

            $this->forge->addColumn('courses', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('level', 'courses')) {
            $this->forge->dropColumn('courses', 'level');
        }
    }
}
