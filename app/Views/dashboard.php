<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Dashboard</h1>
                <div>
                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?> me-2">
                        <?= ucfirst($user['role']) ?>
                    </span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary">Logout</a>
                </div>
            </div> 

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Welcome, <?= esc($user['name']) ?>!</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">You are successfully logged in to the Learning Management System.</p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6>Account Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Name:</strong> <?= esc($user['name']) ?></li>
                                        <li><strong>Email:</strong> <?= esc($user['email']) ?></li>
                                        <li><strong>Role:</strong> <?= ucfirst($user['role']) ?></li>
                                        <li><strong>User ID:</strong> #<?= $user['id'] ?></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <a href="#" class="btn btn-outline-primary">Manage Users</a>
                                            <a href="#" class="btn btn-outline-primary">System Settings</a>
                                        <?php else: ?>
                                            <a href="#" class="btn btn-outline-primary">My Courses</a>
                                            <a href="#" class="btn btn-outline-primary">My Assignments</a>
                                        <?php endif; ?>
                                        <a href="#" class="btn btn-outline-secondary">Profile Settings</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Recent Activity</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">No recent activity to display.</p>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">System Status</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span>Database:</span>
                                <span class="badge bg-success">Connected</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Session:</span>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Last Login:</span>
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


