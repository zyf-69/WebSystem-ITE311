<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'title',
        'content',
        'posted_by',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'content' => 'required|min_length[10]',
        'posted_by' => 'required|integer'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Announcement title is required',
            'min_length' => 'Title must be at least 3 characters long',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'content' => [
            'required' => 'Announcement content is required',
            'min_length' => 'Content must be at least 10 characters long'
        ]
    ];

    /**
     * Get all announcements with user information
     * 
     * @return array
     */
    public function getAllWithUser()
    {
        return $this->select('announcements.*, users.name as posted_by_name')
                    ->join('users', 'users.id = announcements.posted_by')
                    ->orderBy('announcements.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get recent announcements
     * 
     * @param int $limit
     * @return array
     */
    public function getRecent($limit = 5)
    {
        return $this->select('announcements.*, users.name as posted_by_name')
                    ->join('users', 'users.id = announcements.posted_by')
                    ->orderBy('announcements.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
