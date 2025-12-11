<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'title',
        'description',
        'instructor_id',
        'start_time',
        'end_time',
        'schedule_days',
        'room',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $skipValidation = true;

    /**
     * Get all available courses
     * 
     * @return array List of all courses
     */
    public function getAllCourses()
    {
        return $this->findAll();
    }

    /**
     * Get course by ID
     * 
     * @param int $id Course ID
     * @return array|null Course data
     */
    public function getCourseById($id)
    {
        return $this->find($id);
    }
}

