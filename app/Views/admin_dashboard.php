<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="bi bi-shield-check"></i> Admin Dashboard</h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-shield-fill-check text-danger" style="font-size: 5rem;"></i>
                    <h2 class="mt-4">Welcome, Admin!</h2>
                    <?php if (isset($user) && $user['name']): ?>
                        <p class="lead text-muted">Hello, <?= esc($user['name']) ?>!</p>
                    <?php endif; ?>
                    <hr class="my-4">
                    <p class="text-muted">You have successfully logged in to the admin dashboard.</p>
                    <p class="text-muted"><strong>Full Admin Access:</strong> You can manage all users, create, edit, and delete accounts.</p>
                    <div class="mt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="<?= base_url('admin/users') ?>" class="btn btn-primary w-100">
                                    <i class="bi bi-people"></i> Manage Users
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="<?= base_url('admin/create-user') ?>" class="btn btn-success w-100">
                                    <i class="bi bi-person-plus"></i> Create New User
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="<?= base_url('announcements') ?>" class="btn btn-info w-100">
                                    <i class="bi bi-megaphone"></i> View Announcements
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary w-100">
                                    <i class="bi bi-speedometer2"></i> Main Dashboard
                                </a>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
