<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-megaphone"></i> Announcements</h1>
                <div>
                    <?php if (isset($user) && $user['role']): ?>
                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?> me-2">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    <?php endif; ?>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
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

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bell"></i> All Announcements
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($announcements)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-3">No announcements available at this time.</p>
                            <p class="text-muted small">Check back later for updates.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($announcements as $announcement): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-2">
                                                <i class="bi bi-megaphone-fill text-primary"></i>
                                                <?= esc($announcement['title']) ?>
                                            </h5>
                                            <p class="mb-2"><?= nl2br(esc($announcement['content'])) ?></p>
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                <span><?= date('F d, Y \a\t g:i A', strtotime($announcement['created_at'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($announcements)): ?>
                <div class="mt-3 text-muted small text-center">
                    <i class="bi bi-info-circle"></i> 
                    Showing <?= count($announcements) ?> announcement<?= count($announcements) !== 1 ? 's' : '' ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
