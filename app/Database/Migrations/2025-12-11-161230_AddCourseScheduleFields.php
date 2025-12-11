<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCourseScheduleFields extends Migration
{
    public function up()
    {
        $fields = [];

        if (! $this->db->fieldExists('start_time', 'courses')) {
            $fields['start_time'] = [
                'type' => 'TIME',
                'null' => true,
                'after' => 'instructor_id',
            ];
        }

        if (! $this->db->fieldExists('end_time', 'courses')) {
            $fields['end_time'] = [
                'type' => 'TIME',
                'null' => true,
                'after' => 'start_time',
            ];
        }

        if (! $this->db->fieldExists('schedule_days', 'courses')) {
            $fields['schedule_days'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'end_time',
            ];
        }

        if (! $this->db->fieldExists('room', 'courses')) {
            $fields['room'] = [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'schedule_days',
            ];
        }

        if (! $this->db->fieldExists('status', 'courses')) {
            $fields['status'] = [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'default' => 'active',
                'after' => 'room',
            ];
        }

        if (! empty($fields)) {
            $this->forge->addColumn('courses', $fields);
        }
    }

    public function down()
    {
        $drop = ['start_time', 'end_time', 'schedule_days', 'room', 'status'];
        foreach ($drop as $col) {
            if ($this->db->fieldExists($col, 'courses')) {
                $this->forge->dropColumn('courses', $col);
            }
        }
    }
}
