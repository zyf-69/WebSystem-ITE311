<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-mortarboard"></i> Student Dashboard</h1>
                <div>
                    <span class="badge bg-primary me-2">Student</span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
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

            <!-- Main Content -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-circle"></i> Welcome, <?= esc($user['name']) ?>!
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Welcome to your Learning Management System dashboard. Here you can access your courses, assignments, and track your progress.</p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6><i class="bi bi-info-circle"></i> Account Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Name:</strong> <?= esc($user['name']) ?></li>
                                        <li><strong>Email:</strong> <?= esc($user['email']) ?></li>
                                        <li><strong>Role:</strong> <span class="badge bg-primary">Student</span></li>
                                        <li><strong>Student ID:</strong> #<?= $user['id'] ?></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="bi bi-lightning-charge"></i> Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <a href="#" class="btn btn-primary">
                                            <i class="bi bi-book"></i> My Courses
                                        </a>
                                        <a href="#" class="btn btn-success">
                                            <i class="bi bi-file-text"></i> My Assignments
                                        </a>
                                        <a href="#" class="btn btn-info">
                                            <i class="bi bi-trophy"></i> My Grades
                                        </a>
                                        <a href="#" class="btn btn-outline-secondary">
                                            <i class="bi bi-gear"></i> Profile Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Courses Section -->
                    <div class="card mt-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-book"></i> My Courses
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (empty($courses)): ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">You are not enrolled in any courses yet.</p>
                                    <a href="#" class="btn btn-primary">Browse Available Courses</a>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($courses as $course): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title"><?= esc($course['title']) ?></h6>
                                                    <p class="card-text small text-muted"><?= esc($course['description']) ?></p>
                                                    <a href="#" class="btn btn-sm btn-primary">View Course</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-bell"></i> Announcements
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">No new announcements.</p>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-clock-history"></i> Recent Activity
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">No recent activity to display.</p>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-graph-up"></i> My Progress
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">Overall Progress</span>
                                    <span class="small">0%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="small">Completed Courses:</span>
                                <span class="badge bg-success">0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="small">In Progress:</span>
                                <span class="badge bg-primary">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
