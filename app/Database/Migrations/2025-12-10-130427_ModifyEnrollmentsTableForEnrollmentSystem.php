<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyEnrollmentsTableForEnrollmentSystem extends Migration
{
    public function up()
    {
        // Add user_id column if it doesn't exist (for compatibility with requirements)
        if (!$this->db->fieldExists('user_id', 'enrollments')) {
            $fields = [
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'id',
                ],
            ];
            $this->forge->addColumn('enrollments', $fields);
            
            // Copy data from student_id to user_id
            $this->db->query('UPDATE enrollments SET user_id = student_id WHERE user_id IS NULL');
            
            // Make user_id NOT NULL after copying
            $this->db->query('ALTER TABLE enrollments MODIFY user_id INT(11) UNSIGNED NOT NULL');
        }
        
        // Add enrollment_date column if it doesn't exist
        if (!$this->db->fieldExists('enrollment_date', 'enrollments')) {
            $fields = [
                'enrollment_date' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'course_id',
                ],
            ];
            $this->forge->addColumn('enrollments', $fields);
            
            // Copy data from created_at to enrollment_date
            $this->db->query('UPDATE enrollments SET enrollment_date = created_at WHERE enrollment_date IS NULL');
        }
    }

    public function down()
    {
        // Remove the columns if they exist
        if ($this->db->fieldExists('user_id', 'enrollments')) {
            $this->forge->dropColumn('enrollments', 'user_id');
        }
        
        if ($this->db->fieldExists('enrollment_date', 'enrollments')) {
            $this->forge->dropColumn('enrollments', 'enrollment_date');
        }
    }
}
