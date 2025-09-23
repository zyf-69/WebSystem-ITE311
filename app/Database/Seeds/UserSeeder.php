<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Admin Users
            [
                'name' => 'System Administrator',
                'email' => 'admin@lms.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@lms.com',
                'password' => password_hash('super123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Regular Users
            [
                'name' => 'John Doe',
                'email' => 'john.doe@lms.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@lms.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Mike Wilson',
                'email' => 'mike.wilson@lms.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@student.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bob Brown',
                'email' => 'bob.brown@student.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Carol Davis',
                'email' => 'carol.davis@student.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert all users
        $this->db->table('users')->insertBatch($data);
        
        echo "Sample users created successfully!\n";
    }
}
