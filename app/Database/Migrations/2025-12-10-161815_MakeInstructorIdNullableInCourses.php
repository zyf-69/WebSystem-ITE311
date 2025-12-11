<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeInstructorIdNullableInCourses extends Migration
{
    public function up()
    {
        $fields = [
            'instructor_id' => [
                'name'       => 'instructor_id',
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ];

        $this->forge->modifyColumn('courses', $fields);
    }

    public function down()
    {
        $fields = [
            'instructor_id' => [
                'name'       => 'instructor_id',
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ];

        $this->forge->modifyColumn('courses', $fields);
    }
}
