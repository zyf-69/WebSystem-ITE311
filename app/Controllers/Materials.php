<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Materials extends Controller
{
    protected $materialModel;
    protected $courseModel;
    protected $enrollmentModel;
    protected $notificationModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
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

        // Check if POST request (case insensitive)
        if (strtolower($this->request->getMethod()) === 'post') {
            $file = $this->request->getFile('material');
            
            // Debug: Check if file was actually uploaded
            if (!$file) {
                $this->session->setFlashdata('error', 'No file was selected. Please choose a file to upload.');
                return redirect()->to('/course/' . $courseId . '/upload');
            }
            
            // Check if file is valid
            if (!$file->isValid()) {
                $errorCode = $file->getError();
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive.',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive.',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                    UPLOAD_ERR_EXTENSION => 'File upload stopped by extension.',
                ];
                $errorMsg = $errorMessages[$errorCode] ?? 'File upload error: ' . $errorCode;
                $this->session->setFlashdata('error', $errorMsg);
                return redirect()->to('/course/' . $courseId . '/upload');
            }

            // Get file extension
            $extension = $file->getClientExtension();
            $allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'rar', 'txt', 'png', 'jpg', 'jpeg', 'mp4', 'mp3'];
            
            // Validate file size (20MB = 20480 KB)
            if ($file->getSize() > 20480 * 1024) {
                $this->session->setFlashdata('error', 'File must be less than 20MB.');
                return redirect()->to('/course/' . $courseId . '/upload');
            }
            
            // Validate file extension
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                $this->session->setFlashdata('error', 'Invalid file type. Allowed: ' . implode(', ', $allowedExtensions));
                return redirect()->to('/course/' . $courseId . '/upload');
            }
            
            // If we get here, file is valid - proceed with upload

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
                if (!is_writable($uploadDir)) {
                    $this->session->setFlashdata('error', 'Upload directory is not writable. Please check permissions.');
                    return redirect()->to('/course/' . $courseId . '/upload');
                }
            }

            $newName = $file->getRandomName();

            if ($file->move($uploadDir, $newName)) {
                $data = [
                    'course_id' => $courseId,
                    'file_name' => $file->getClientName(),
                    'file_path' => 'uploads/materials/' . $newName,
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $insertId = $this->materialModel->insert($data);
                if ($insertId) {
                    // Get uploaded by user info
                    $uploadedBy = $this->userModel->find($this->session->get('user_id'));
                    $uploadedByName = $uploadedBy ? $uploadedBy['name'] : 'Teacher';
                    $fileName = $file->getClientName();
                    
                    // Create notifications for all enrolled students in this course
                    $enrolledStudents = $this->enrollmentModel
                        ->where('course_id', $courseId)
                        ->where('status', 'enrolled')
                        ->findAll();
                    
                    // Create notifications for all enrolled students in this course
                    foreach ($enrolledStudents as $enrollment) {
                        $studentId = $enrollment['user_id'] ?? $enrollment['student_id'];
                        if ($studentId) {
                            $notificationData = [
                                'user_id' => $studentId,
                                'message' => "New material [{$fileName}] has been uploaded for course [{$course['title']}]",
                                'is_read' => 0,
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                            $this->notificationModel->createNotification($notificationData);
                        }
                    }
                    
                    // Create notifications for all admins
                    $admins = $this->userModel->where('role', 'admin')->findAll();
                    if (!empty($admins)) {
                        foreach ($admins as $admin) {
                            $adminNotification = [
                                'user_id' => $admin['id'],
                                'message' => "[{$uploadedByName}] uploaded new material [{$fileName}] for course [{$course['title']}]",
                                'is_read' => 0,
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                            $this->notificationModel->createNotification($adminNotification);
                        }
                    }
                    
                    // Also notify the teacher who uploaded (for confirmation)
                    $teacherId = $this->session->get('user_id');
                    if ($teacherId) {
                        $teacherNotification = [
                            'user_id' => $teacherId,
                            'message' => "You have successfully uploaded material [{$fileName}] for course [{$course['title']}]",
                            'is_read' => 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        $this->notificationModel->createNotification($teacherNotification);
                    }
                    
                    $this->session->setFlashdata('success', 'Material uploaded successfully and saved to database.');
                    return redirect()->to('/course/' . $courseId . '/upload');
                } else {
                    // Delete uploaded file if database insert failed
                    @unlink($uploadDir . DIRECTORY_SEPARATOR . $newName);
                    $errors = $this->materialModel->errors();
                    $errorMsg = !empty($errors) ? implode(', ', $errors) : 'Failed to save material information to database.';
                    $this->session->setFlashdata('error', $errorMsg);
                    return redirect()->to('/course/' . $courseId . '/upload');
                }
            } else {
                $errors = $file->getErrorString();
                $this->session->setFlashdata('error', 'Failed to upload file: ' . $errors);
                return redirect()->to('/course/' . $courseId . '/upload');
            }
        }

        $materials = $this->materialModel->getMaterialsByCourse($courseId);

        $data = [
            'title' => 'Upload Course Materials',
            'course' => $course,
            'materials' => $materials,
            'validation' => $this->validator,
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

        // Get course and file info before deletion
        $course = $this->courseModel->find($material['course_id']);
        $fileName = $material['file_name'];
        $courseId = $material['course_id'];
        
        // Get deleted by user info
        $deletedBy = $this->userModel->find($this->session->get('user_id'));
        $deletedByName = $deletedBy ? $deletedBy['name'] : 'User';

        // Delete the physical file
        $absolutePath = rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $material['file_path'];
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }

        // Delete from database
        $deleteResult = $this->materialModel->delete($materialId);
        
        // Only create notifications if deletion was successful
        if ($deleteResult) {
            // Create notifications for all enrolled students in this course
            $enrolledStudents = $this->enrollmentModel
                ->where('course_id', $courseId)
                ->where('status', 'enrolled')
                ->findAll();
            
            foreach ($enrolledStudents as $enrollment) {
                $studentId = $enrollment['user_id'] ?? $enrollment['student_id'];
                if ($studentId) {
                    $notificationData = [
                        'user_id' => $studentId,
                        'message' => "Material [{$fileName}] has been deleted from course [{$course['title']}]",
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->notificationModel->createNotification($notificationData);
                }
            }
            
            // Create notifications for all admins
            $admins = $this->userModel->where('role', 'admin')->findAll();
            foreach ($admins as $admin) {
                $adminNotification = [
                    'user_id' => $admin['id'],
                    'message' => "[{$deletedByName}] deleted material [{$fileName}] from course [{$course['title']}]",
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->notificationModel->createNotification($adminNotification);
            }
            
            // Notify the course instructor (if different from the one who deleted)
            if ($course && !empty($course['instructor_id'])) {
                $instructorId = (int) $course['instructor_id'];
                $currentUserId = (int) $this->session->get('user_id');
                
                // Only notify if instructor is different from the one who deleted
                if ($instructorId !== $currentUserId) {
                    $instructorNotification = [
                        'user_id' => $instructorId,
                        'message' => "Material [{$fileName}] has been deleted from your course [{$course['title']}]",
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->notificationModel->createNotification($instructorNotification);
                }
            }
            
            // Also notify the person who deleted (for confirmation)
            $currentUserId = (int) $this->session->get('user_id');
            $deleterNotification = [
                'user_id' => $currentUserId,
                'message' => "You have successfully deleted material [{$fileName}] from course [{$course['title']}]",
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->notificationModel->createNotification($deleterNotification);
        }
        
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
        
        // Check if student is enrolled (status = 'enrolled')
        $isEnrolled = $this->enrollmentModel
            ->groupStart()
            ->where('user_id', $userId)
            ->orWhere('student_id', $userId)
            ->groupEnd()
            ->where('course_id', $material['course_id'])
            ->where('status', 'enrolled')
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

