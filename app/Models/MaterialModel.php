<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'course_id',
        'file_name',
        'file_path',
        'created_at',
    ];

    public $useTimestamps = false;

    public function insertMaterial(array $data): bool
    {
        return (bool) $this->insert($data);
    }

    public function getMaterialsByCourse(int $courseId): array
    {
        return $this->where('course_id', $courseId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}

