<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id',
        'course_id',
        'enrollment_date',
        'student_id', // Keep for backward compatibility
        'status',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $skipValidation = true;

    /**
     * Enroll a user in a course
     * 
     * @param array $data Enrollment data (user_id, course_id, enrollment_date)
     * @return bool|int Insert ID on success, false on failure
     */
    public function enrollUser($data)
    {
        // Ensure enrollment_date is set
        if (!isset($data['enrollment_date'])) {
            $data['enrollment_date'] = date('Y-m-d H:i:s');
        }

        // Use student_id if user_id not provided (for backward compatibility)
        if (!isset($data['user_id']) && isset($data['student_id'])) {
            $data['user_id'] = $data['student_id'];
        }

        return $this->insert($data);
    }

    /**
     * Get all courses a user is enrolled in
     * 
     * @param int $user_id User ID
     * @return array List of enrolled courses
     */
    public function getUserEnrollments($user_id, $status = null)
    {
        $db = \Config\Database::connect();

        // Join with courses table to get course details
        $builder = $db->table('enrollments');

        // Build select dynamically to avoid selecting columns that may not exist
        $selectFields = ['enrollments.*', 'courses.id as course_id', 'courses.title', 'courses.description', 'courses.instructor_id'];

        // Check and add optional course fields if they exist
        $optionalFields = [
            'start_time' => 'courses.start_time',
            'end_time' => 'courses.end_time',
            'schedule_days' => 'courses.schedule_days',
            'room' => 'courses.room',
            'status' => 'courses.status as course_status'
        ];

        foreach ($optionalFields as $field => $select) {
            if ($db->fieldExists($field, 'courses')) {
                $selectFields[] = $select;
            }
        }

        $builder->select(implode(', ', $selectFields));
        $builder->join('courses', 'courses.id = enrollments.course_id', 'left');
        
        // Check both user_id and student_id for compatibility
        $builder->groupStart()
                ->where('enrollments.user_id', $user_id)
                ->orWhere('enrollments.student_id', $user_id)
                ->groupEnd();
        
        // Exclude soft-deleted enrollments if deleted_at column exists
        if ($db->fieldExists('deleted_at', 'enrollments')) {
            $builder->where('enrollments.deleted_at', null);
        }
        
        // Filter by status if provided
        if ($status !== null) {
            $builder->where('enrollments.status', $status);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Check if a user is already enrolled in a specific course
     * Prevents duplicate enrollments
     * 
     * @param int $user_id User ID
     * @param int $course_id Course ID
     * @return bool True if already enrolled, false otherwise
     */
    public function isAlreadyEnrolled($user_id, $course_id)
    {
        $builder = $this->db->table($this->table);
        
        // Check both user_id and student_id for compatibility
        $builder->groupStart()
                ->where('user_id', $user_id)
                ->orWhere('student_id', $user_id)
                ->groupEnd();
        
        $builder->where('course_id', $course_id);
        
        // Exclude soft-deleted enrollments if deleted_at column exists
        if ($this->db->fieldExists('deleted_at', $this->table)) {
            $builder->where('deleted_at', null);
        }
        
        $result = $builder->get()->getRowArray();
        
        return !empty($result);
    }
}

