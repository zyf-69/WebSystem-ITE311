<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="bi bi-upload"></i> Upload Materials</h1>
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

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Course: <?= esc($course['title'] ?? 'Unknown Course') ?></h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('course/' . $course['id'] . '/upload') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="material" class="form-label">Select File <span class="text-danger">*</span></label>
                    <input type="file" class="form-control <?= isset($validation) && $validation->hasError('material') ? 'is-invalid' : '' ?>" id="material" name="material" required accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar,.txt,.png,.jpg,.jpeg,.mp4,.mp3">
                    <div class="form-text">Allowed: pdf, doc, docx, ppt, pptx, zip, rar, txt, png, jpg, jpeg, mp4, mp3. Max 20MB.</div>
                    <?php if (isset($validation) && $validation->hasError('material')): ?>
                        <div class="invalid-feedback d-block">
                            <?= $validation->getError('material') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload"></i> Upload
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0">Existing Materials</h5>
        </div>
        <div class="card-body">
            <?php if (empty($materials)): ?>
                <p class="text-muted mb-0">No materials uploaded yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Uploaded At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materials as $material): ?>
                                <tr>
                                    <td><?= esc($material['file_name']) ?></td>
                                    <td><?= esc(date('M d, Y H:i', strtotime($material['created_at']))) ?></td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="<?= base_url('materials/download/' . $material['id']) ?>">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        <a class="btn btn-sm btn-outline-danger" href="<?= base_url('materials/delete/' . $material['id']) ?>" onclick="return confirm('Delete this material?');">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

