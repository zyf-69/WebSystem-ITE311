<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeclineReasonToEnrollments extends Migration
{
    public function up()
    {
        // Add decline_reason column if it doesn't exist
        if (!$this->db->fieldExists('decline_reason', 'enrollments')) {
            $fields = [
                'decline_reason' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'status',
                ],
            ];
            $this->forge->addColumn('enrollments', $fields);
        }
    }

    public function down()
    {
        // Remove the column if it exists
        if ($this->db->fieldExists('decline_reason', 'enrollments')) {
            $this->forge->dropColumn('enrollments', 'decline_reason');
        }
    }
}
