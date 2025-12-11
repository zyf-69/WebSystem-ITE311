<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Get some teachers for instructors
        $db = \Config\Database::connect();
        $teachers = $db->table('users')
                      ->where('role', 'teacher')
                      ->get()
                      ->getResultArray();
        
        if (empty($teachers)) {
            echo "No teachers found. Please run TeacherSeeder first.\n";
            return;
        }
        
        $instructorId = $teachers[0]['id']; // Use first teacher as instructor
        
        $data = [
            [
                'title' => 'Introduction to Digital Arts',
                'description' => 'Learn the fundamentals of digital arts including design principles, color theory, and basic tools.',
                'instructor_id' => $instructorId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Web Design Fundamentals',
                'description' => 'Master the basics of web design including HTML, CSS, and responsive design principles.',
                'instructor_id' => $instructorId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Graphic Design Principles',
                'description' => 'Explore typography, layout design, and visual communication in graphic design.',
                'instructor_id' => $instructorId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'UI/UX Design',
                'description' => 'Learn user interface and user experience design for modern applications.',
                'instructor_id' => $instructorId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Digital Photography',
                'description' => 'Master digital photography techniques, composition, and post-processing.',
                'instructor_id' => $instructorId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert courses
        $db->table('courses')->insertBatch($data);
        
        echo "Sample courses created successfully!\n";
    }
}

