<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
                <div>
                    <?php if ($user['role'] === 'admin'): ?>
                        <span class="badge bg-danger me-2">Administrator</span>
                    <?php elseif ($user['role'] === 'teacher'): ?>
                        <span class="badge bg-success me-2">Teacher</span>
                    <?php else: ?>
                        <span class="badge bg-primary me-2">Student</span>
                    <?php endif; ?>
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

            <!-- Role-based Statistics Cards -->
            <?php if ($user['role'] === 'admin'): ?>
                <!-- Admin Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Total Users</h6>
                                        <h2 class="mt-2"><?= $roleData['total_users'] ?></h2>
                                    </div>
                                    <i class="bi bi-people fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Administrators</h6>
                                        <h2 class="mt-2"><?= $roleData['admin_count'] ?></h2>
                                    </div>
                                    <i class="bi bi-shield-check fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Teachers</h6>
                                        <h2 class="mt-2"><?= $roleData['teacher_count'] ?></h2>
                                    </div>
                                    <i class="bi bi-person-badge fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Students</h6>
                                        <h2 class="mt-2"><?= $roleData['student_count'] ?></h2>
                                    </div>
                                    <i class="bi bi-mortarboard fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($user['role'] === 'teacher'): ?>
                <!-- Teacher Statistics -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">My Courses</h6>
                                        <h2 class="mt-2"><?= count($roleData['courses']) ?></h2>
                                    </div>
                                    <i class="bi bi-book fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">My Students</h6>
                                        <h2 class="mt-2"><?= $roleData['students'] ?></h2>
                                    </div>
                                    <i class="bi bi-people fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Student Statistics -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Enrolled Courses</h6>
                                        <h2 class="mt-2"><?= count($roleData['enrolled_courses']) ?></h2>
                                    </div>
                                    <i class="bi bi-book fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Assignments</h6>
                                        <h2 class="mt-2"><?= count($roleData['assignments']) ?></h2>
                                    </div>
                                    <i class="bi bi-file-text fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Main Content -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'success' : 'primary') ?> text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-circle"></i> Welcome, <?= esc($user['name']) ?>!
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($user['role'] === 'admin'): ?>
                                <p class="card-text">You are logged in as an administrator. You have full access to manage the Learning Management System.</p>
                            <?php elseif ($user['role'] === 'teacher'): ?>
                                <p class="card-text">You are logged in as a teacher. You can manage your courses, students, and assignments.</p>
                            <?php else: ?>
                                <p class="card-text">You are logged in as a student. Access your courses, assignments, and learning materials.</p>
                            <?php endif; ?>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6><i class="bi bi-info-circle"></i> Account Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Name:</strong> <?= esc($user['name']) ?></li>
                                        <li><strong>Email:</strong> <?= esc($user['email']) ?></li>
                                        <li><strong>Role:</strong> 
                                            <?php if ($user['role'] === 'admin'): ?>
                                                <span class="badge bg-danger">Administrator</span>
                                            <?php elseif ($user['role'] === 'teacher'): ?>
                                                <span class="badge bg-success">Teacher</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary">Student</span>
                                            <?php endif; ?>
                                        </li>
                                        <li><strong>User ID:</strong> #<?= $user['id'] ?></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="bi bi-lightning-charge"></i> Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <a href="<?= base_url('admin/users') ?>" class="btn btn-primary">
                                                <i class="bi bi-people"></i> Manage Users
                                            </a>
                                            <a href="<?= base_url('admin/create-user') ?>" class="btn btn-success">
                                                <i class="bi bi-person-plus"></i> Create New User
                                            </a>
                                            <a href="<?= base_url('announcements') ?>" class="btn btn-outline-primary">
                                                <i class="bi bi-megaphone"></i> View Announcements
                                            </a>
                                        <?php elseif ($user['role'] === 'teacher'): ?>
                                            <a href="<?= base_url('announcements') ?>" class="btn btn-primary">
                                                <i class="bi bi-megaphone"></i> View Announcements
                                            </a>
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="bi bi-book"></i> My Courses
                                            </a>
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="bi bi-people"></i> My Students
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('announcements') ?>" class="btn btn-primary">
                                                <i class="bi bi-megaphone"></i> View Announcements
                                            </a>
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="bi bi-book"></i> My Courses
                                            </a>
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="bi bi-file-text"></i> My Assignments
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
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
                                <i class="bi bi-hdd-network"></i> System Status
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-database"></i> Database:</span>
                                <span class="badge bg-success">Connected</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-key"></i> Session:</span>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="bi bi-clock"></i> Last Login:</span>
                                <span class="text-muted small">Just now</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
