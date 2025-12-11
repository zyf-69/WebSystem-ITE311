<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-people"></i> My Students</h1>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h6 class="card-title">Total Students</h6>
                            <h2><?= $totalStudents ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h6 class="card-title">Pending Requests</h6>
                            <h2><?= $pendingCount ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h6 class="card-title">Enrolled Students</h6>
                            <h2><?= $enrolledCount ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (empty($courseEnrollments)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No courses assigned to you yet. Please contact an administrator.
                </div>
            <?php else: ?>
                <?php foreach ($courseEnrollments as $courseData): ?>
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-book"></i> <?= esc($courseData['course']['title']) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($courseData['enrollments'])): ?>
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> No students enrolled in this course yet.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Enrollment Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($courseData['enrollments'] as $enrollment): ?>
                                                <tr>
                                                    <td><?= esc($enrollment['student_name'] ?? 'Unknown') ?></td>
                                                    <td><?= esc($enrollment['student_email'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <?php 
                                                        $status = $enrollment['status'] ?? 'pending';
                                                        $statusClass = $status === 'enrolled' ? 'success' : ($status === 'pending' ? 'warning' : 'danger');
                                                        ?>
                                                        <span class="badge bg-<?= $statusClass ?>">
                                                            <?= ucfirst(esc($status)) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?= date('M d, Y', strtotime($enrollment['enrollment_date'] ?? $enrollment['created_at'])) ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($status === 'pending'): ?>
                                                            <a href="<?= base_url('teacher/accept-enrollment/' . $enrollment['id']) ?>" 
                                                               class="btn btn-sm btn-success"
                                                               onclick="return confirm('Accept this enrollment request?');">
                                                                <i class="bi bi-check-circle"></i> Accept
                                                            </a>
                                                            <a href="<?= base_url('teacher/decline-enrollment/' . $enrollment['id']) ?>" 
                                                               class="btn btn-sm btn-danger"
                                                               onclick="return confirm('Decline this enrollment request?');">
                                                                <i class="bi bi-x-circle"></i> Decline
                                                            </a>
                                                        <?php elseif ($status === 'enrolled'): ?>
                                                            <a href="<?= base_url('teacher/unenroll-student/' . $enrollment['id']) ?>" 
                                                               class="btn btn-sm btn-danger"
                                                               onclick="return confirm('Are you sure you want to unenroll this student?');">
                                                                <i class="bi bi-person-x"></i> Unenroll
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

