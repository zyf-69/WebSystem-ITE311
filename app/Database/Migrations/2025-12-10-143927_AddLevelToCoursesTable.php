<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLevelToCoursesTable extends Migration
{
    public function up()
    {
        // Add 'level' column to courses if it does not exist
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
        // Drop 'level' column if it exists
        if ($this->db->fieldExists('level', 'courses')) {
            $this->forge->dropColumn('courses', 'level');
        }
    }
}
