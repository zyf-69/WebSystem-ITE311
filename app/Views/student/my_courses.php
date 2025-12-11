<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-book"></i> My Courses</h1>
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

            <!-- Pending Enrollment Requests -->
            <?php if (!empty($pendingEnrollments)): ?>
                <div class="card mb-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-hourglass-split"></i> Pending Enrollment Requests
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($pendingEnrollments as $enrollment): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <i class="bi bi-clock-history text-warning"></i>
                                                <?= esc($enrollment['title'] ?? 'Course #' . $enrollment['course_id']) ?>
                                            </h6>
                                            <p class="mb-1 small text-muted">
                                                <?= esc($enrollment['description'] ?? 'No description available') ?>
                                            </p>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> 
                                                Requested: <?= date('M d, Y', strtotime($enrollment['enrollment_date'] ?? $enrollment['created_at'])) ?>
                                            </small>
                                        </div>
                                        <div>
                                            <span class="badge bg-warning">Pending Approval</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Enrolled Courses -->
            <?php if (empty($enrolledCourses)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> You are not enrolled in any courses yet. Browse available courses on the dashboard to enroll.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($enrolledCourses as $enrollment): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-book-fill"></i> <?= esc($enrollment['title'] ?? 'Course #' . $enrollment['course_id']) ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?= esc($enrollment['description'] ?? 'No description available') ?></p>
                                    
                                    <div class="mb-2">
                                        <?php if (!empty($enrollment['level'])): ?>
                                            <span class="badge bg-info"><?= esc($enrollment['level']) ?></span>
                                        <?php endif; ?>
                                        <span class="badge bg-success">Enrolled</span>
                                    </div>
                                    
                                    <?php if (!empty($enrollment['start_time']) && !empty($enrollment['end_time'])): ?>
                                        <p class="mb-1">
                                            <i class="bi bi-clock"></i> 
                                            <?= date('h:i A', strtotime($enrollment['start_time'])) ?> - 
                                            <?= date('h:i A', strtotime($enrollment['end_time'])) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($enrollment['schedule_days'])): ?>
                                        <p class="mb-1">
                                            <i class="bi bi-calendar"></i> 
                                            <?= esc($enrollment['schedule_days']) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($enrollment['room'])): ?>
                                        <p class="mb-1">
                                            <i class="bi bi-geo-alt"></i> 
                                            <?= esc($enrollment['room']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-light">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check"></i> 
                                        Enrolled: <?= date('M d, Y', strtotime($enrollment['enrollment_date'] ?? $enrollment['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

