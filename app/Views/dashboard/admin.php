<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-speedometer2"></i> Admin Dashboard</h1>
                <div>
                    <span class="badge bg-danger me-2">Administrator</span>
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

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Users</h6>
                                    <h2 class="mt-2"><?= $stats['total_users'] ?></h2>
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
                                    <h2 class="mt-2"><?= $stats['admin_count'] ?></h2>
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
                                    <h6 class="card-title mb-0">Students</h6>
                                    <h2 class="mt-2"><?= $stats['student_count'] ?></h2>
                                </div>
                                <i class="bi bi-mortarboard fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Courses</h6>
                                    <h2 class="mt-2"><?= $stats['total_courses'] ?></h2>
                                </div>
                                <i class="bi bi-book fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                            <p class="card-text">You are logged in as an administrator. You have full access to manage the Learning Management System.</p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6><i class="bi bi-info-circle"></i> Account Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Name:</strong> <?= esc($user['name']) ?></li>
                                        <li><strong>Email:</strong> <?= esc($user['email']) ?></li>
                                        <li><strong>Role:</strong> <span class="badge bg-danger">Administrator</span></li>
                                        <li><strong>User ID:</strong> #<?= $user['id'] ?></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="bi bi-lightning-charge"></i> Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <a href="<?= base_url('admin/users') ?>" class="btn btn-primary">
                                            <i class="bi bi-people"></i> Manage Users
                                        </a>
                                        <a href="<?= base_url('admin/create-user') ?>" class="btn btn-success">
                                            <i class="bi bi-person-plus"></i> Create New User
                                        </a>
                                        <a href="<?= base_url('course/1/upload') ?>" class="btn btn-warning">
                                            <i class="bi bi-upload"></i> Attach File to Course
                                        </a>
                                        <a href="#" class="btn btn-outline-primary">
                                            <i class="bi bi-gear"></i> System Settings
                                        </a>
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
