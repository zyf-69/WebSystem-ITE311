<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the Student Portal',
                'content' => 'We are excited to announce the launch of our new student portal. Please explore the features and let us know your feedback.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'title' => 'Midterm Examination Schedule',
                'content' => 'The midterm examinations will be held from October 20-25, 2025. Please check your individual schedules for specific exam times and locations.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert announcements
        $this->db->table('announcements')->insertBatch($data);
        
        echo "Sample announcements created successfully!\n";
    }
}
