<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the New Academic Year!',
                'content' => 'We are excited to welcome all students to the new academic year. Please check your schedules and make sure you are enrolled in all your classes. If you have any questions, please contact the registrar\'s office.',
                'posted_by' => 1, // Admin user
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'title' => 'Midterm Examination Schedule',
                'content' => 'The midterm examinations will be held from October 20-25, 2025. Please review the examination schedule posted on your department bulletin board. Good luck to all students!',
                'posted_by' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'title' => 'Library Hours Extended',
                'content' => 'Due to the upcoming midterm exams, the library will extend its operating hours. The library will now be open from 7:00 AM to 10:00 PM, Monday through Saturday. Sunday hours remain 9:00 AM to 5:00 PM.',
                'posted_by' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'title' => 'System Maintenance Notice',
                'content' => 'The student portal will undergo scheduled maintenance on October 19, 2025, from 2:00 AM to 4:00 AM. During this time, the system will be unavailable. We apologize for any inconvenience.',
                'posted_by' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'title' => 'Important: Grade Submission Deadline',
                'content' => 'All faculty members are reminded that the deadline for submitting midterm grades is October 28, 2025. Please ensure all grades are entered into the system before the deadline to avoid delays in grade processing.',
                'posted_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert all announcements
        $this->db->table('announcements')->insertBatch($data);
        
        echo "Sample announcements created successfully!\n";
    }
}
