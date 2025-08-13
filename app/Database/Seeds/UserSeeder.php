<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name'          => 'Admin User',
                'email'         => 'admin@example.com',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
                'role'          => 'admin',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Instructor One',
                'email'         => 'instructor@example.com',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
                'role'          => 'instructor',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Student One',
                'email'         => 'student@example.com',
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
                'role'          => 'student',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
