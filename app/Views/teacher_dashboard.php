<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-person-badge"></i> Teacher Dashboard</h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-person-check-fill text-success" style="font-size: 5rem;"></i>
                    <h2 class="mt-4">Welcome, Teacher!</h2>
                    <?php if (isset($user) && $user['name']): ?>
                        <p class="lead text-muted">Hello, <?= esc($user['name']) ?>!</p>
                    <?php endif; ?>
                    <hr class="my-4">
                    <p class="text-muted">You have successfully logged in to the teacher dashboard.</p>
                    <div class="mt-4">
                        <a href="<?= base_url('announcements') ?>" class="btn btn-primary me-2">
                            <i class="bi bi-megaphone"></i> View Announcements
                        </a>
                        <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
