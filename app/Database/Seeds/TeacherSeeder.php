<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Prof. Maria Santos',
                'email' => 'maria.santos@lms.com',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'role' => 'teacher',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Prof. Juan Dela Cruz',
                'email' => 'juan.delacruz@lms.com',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'role' => 'teacher',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert teachers
        $this->db->table('users')->insertBatch($data);
        
        echo "Sample teachers created successfully!\n";
    }
}
