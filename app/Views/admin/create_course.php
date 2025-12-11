<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-plus-circle"></i> Add New Course</h1>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Course Information</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('admin/create-course') ?>">
                        <div class="mb-3">
                            <label for="title" class="form-label">Course Title <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($validation) && $validation->hasError('title') ? 'is-invalid' : '' ?>" 
                                    id="title" 
                                    name="title" 
                                    required>
                                <option value="">Select Course Title</option>
                                <?php foreach ($availableTitles as $title): ?>
                                    <option value="<?= esc($title) ?>" <?= old('title') === $title ? 'selected' : '' ?>>
                                        <?= esc($title) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Select from available course titles</div>
                            <?php if (isset($validation) && $validation->hasError('title')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('title') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?= isset($validation) && $validation->hasError('description') ? 'is-invalid' : '' ?>" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Enter course description (optional)"><?= old('description') ?></textarea>
                            <?php if (isset($validation) && $validation->hasError('description')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('description') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="level" class="form-label">Year Level</label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('level') ? 'is-invalid' : '' ?>" 
                                        id="level" 
                                        name="level">
                                    <option value="">Select Year Level</option>
                                    <option value="1st Year" <?= old('level') === '1st Year' ? 'selected' : '' ?>>1st Year</option>
                                    <option value="2nd Year" <?= old('level') === '2nd Year' ? 'selected' : '' ?>>2nd Year</option>
                                    <option value="3rd Year" <?= old('level') === '3rd Year' ? 'selected' : '' ?>>3rd Year</option>
                                    <option value="4th Year" <?= old('level') === '4th Year' ? 'selected' : '' ?>>4th Year</option>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('level')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('level') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                                        id="status" 
                                        name="status">
                                    <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    <option value="completed" <?= old('status') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('status')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('status') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Course
                            </button>
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

