<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CreateAdminUser extends Seeder
{
    public function run()
    {
        // I-customize mo ang details dito
        $adminData = [
            'name' => 'New Administrator',
            'email' => 'newadmin@lms.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // I-check muna kung existing na ang email
        $existingUser = $this->db->table('users')
                                 ->where('email', $adminData['email'])
                                 ->get()
                                 ->getRowArray();

        if ($existingUser) {
            echo "❌ User with email '{$adminData['email']}' already exists!\n";
            echo "   Please use a different email or update the existing user.\n";
            return;
        }

        // I-insert ang bagong admin user
        $this->db->table('users')->insert($adminData);
        
        echo "✅ New admin user created successfully!\n";
        echo "   Name: {$adminData['name']}\n";
        echo "   Email: {$adminData['email']}\n";
        echo "   Password: admin123\n";
        echo "   Role: Admin\n";
    }
}

