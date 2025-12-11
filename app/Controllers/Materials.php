<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use CodeIgniter\Controller;

class Materials extends Controller
{
    protected $materialModel;
    protected $courseModel;
    protected $enrollmentModel;
    protected $session;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->session = session();
    }

    public function upload($courseId)
    {
        $check = $this->ensureAdminOrInstructor($courseId);
        if ($check !== null) {
            return $check;
        }

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            $this->session->setFlashdata('error', 'Course not found.');
            return redirect()->to('/dashboard');
        }

        helper(['form']);

        if ($this->request->getMethod() === 'post') {
            $file = $this->request->getFile('material');
            
            // Check if file was uploaded
            if (!$file || !$file->isValid()) {
                $this->session->setFlashdata('error', 'No file was uploaded or file is invalid.');
                return redirect()->to('/course/' . $courseId . '/upload');
            }

            $validationRules = [
                'material' => [
                    'rules' => 'uploaded[material]|max_size[material,20480]|ext_in[material,pdf,doc,docx,ppt,pptx,zip,rar,txt,png,jpg,jpeg,mp4,mp3]',
                    'errors' => [
                        'uploaded' => 'Please choose a file to upload.',
                        'max_size' => 'File must be less than 20MB.',
                        'ext_in'   => 'Invalid file type. Allowed: pdf, doc, docx, ppt, pptx, zip, rar, txt, png, jpg, jpeg, mp4, mp3',
                    ],
                ],
            ];

            if ($this->validate($validationRules)) {
                $uploadDir = rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'materials';

                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0755, true)) {
                        $this->session->setFlashdata('error', 'Failed to create upload directory.');
                        return redirect()->to('/course/' . $courseId . '/upload');
                    }
                }

                // Check if directory is writable
                if (!is_writable($uploadDir)) {
                    chmod($uploadDir, 0755);
                }

                $newName = $file->getRandomName();

                if ($file->move($uploadDir, $newName)) {
                    $data = [
                        'course_id' => $courseId,
                        'file_name' => $file->getClientName(),
                        'file_path' => 'uploads/materials/' . $newName,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];

                    if ($this->materialModel->insertMaterial($data)) {
                        $this->session->setFlashdata('success', 'Material uploaded successfully.');
                        return redirect()->to('/course/' . $courseId . '/upload');
                    } else {
                        // Delete uploaded file if database insert failed
                        @unlink($uploadDir . DIRECTORY_SEPARATOR . $newName);
                        $this->session->setFlashdata('error', 'Failed to save material information to database.');
                        return redirect()->to('/course/' . $courseId . '/upload');
                    }
                } else {
                    $errors = $file->getErrorString();
                    $this->session->setFlashdata('error', 'Failed to upload file: ' . $errors);
                    return redirect()->to('/course/' . $courseId . '/upload');
                }
            } else {
                // Get all validation errors
                $errors = $this->validator->getErrors();
                $errorMessage = !empty($errors) ? implode(', ', $errors) : 'Validation failed.';
                $this->session->setFlashdata('error', $errorMessage);
                return redirect()->to('/course/' . $courseId . '/upload');
            }
        }

        $materials = $this->materialModel->getMaterialsByCourse($courseId);

        $data = [
            'title' => 'Upload Course Materials',
            'course' => $course,
            'materials' => $materials,
        ];

        return view('admin/material_upload', $data);
    }

    public function delete($materialId)
    {
        $material = $this->materialModel->find($materialId);
        if (!$material) {
            $this->session->setFlashdata('error', 'Material not found.');
            return redirect()->back();
        }

        $check = $this->ensureAdminOrInstructor($material['course_id']);
        if ($check !== null) {
            return $check;
        }

        $absolutePath = rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $material['file_path'];
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }

        $this->materialModel->delete($materialId);
        $this->session->setFlashdata('success', 'Material deleted successfully.');
        return redirect()->to('/course/' . $material['course_id'] . '/upload');
    }

    public function download($materialId)
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $material = $this->materialModel->find($materialId);
        if (!$material) {
            $this->session->setFlashdata('error', 'Material not found.');
            return redirect()->back();
        }

        $course = $this->courseModel->find($material['course_id']);
        $userId = $this->session->get('user_id');
        $role = strtolower($this->session->get('role') ?? '');

        $isAdmin = ($role === 'admin');
        $isInstructor = ($role === 'teacher' && $course && (int) ($course['instructor_id'] ?? 0) === (int) $userId);
        $isEnrolled = $this->enrollmentModel
            ->groupStart()
            ->where('user_id', $userId)
            ->orWhere('student_id', $userId)
            ->groupEnd()
            ->where('course_id', $material['course_id'])
            ->countAllResults() > 0;

        if (!($isAdmin || $isInstructor || $isEnrolled)) {
            $this->session->setFlashdata('error', 'You are not allowed to download this material.');
            return redirect()->to('/dashboard');
        }

        $absolutePath = rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $material['file_path'];
        if (!is_file($absolutePath)) {
            $this->session->setFlashdata('error', 'File not found on server.');
            return redirect()->back();
        }

        return $this->response->download($absolutePath, null)->setFileName($material['file_name']);
    }

    private function ensureAdminOrInstructor(int $courseId)
    {
        if (!$this->session->get('isLoggedIn')) {
            $this->session->setFlashdata('error', 'Please login first.');
            return redirect()->to('/login');
        }

        $role = strtolower($this->session->get('role'));
        $userId = (int) $this->session->get('user_id');

        if ($role === 'admin') {
            return null;
        }

        if ($role === 'teacher') {
            $course = $this->courseModel->find($courseId);
            if ($course && (int) ($course['instructor_id'] ?? 0) === $userId) {
                return null;
            }
        }

        $this->session->setFlashdata('error', 'Access denied.');
        return redirect()->to('/dashboard');
    }
}

