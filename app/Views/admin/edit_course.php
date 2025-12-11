<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-pencil"></i> Edit Course</h1>
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
                    <form method="post" action="<?= base_url('admin/edit-course/' . $course['id']) ?>">
                        <div class="mb-3">
                            <label for="title" class="form-label">Course Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?= isset($validation) && $validation->hasError('title') ? 'is-invalid' : '' ?>" 
                                   id="title" 
                                   name="title" 
                                   value="<?= old('title', $course['title']) ?>" 
                                   required>
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
                                      rows="3"><?= old('description', $course['description'] ?? '') ?></textarea>
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
                                    <option value="1st Year" <?= old('level', $course['level'] ?? '') === '1st Year' ? 'selected' : '' ?>>1st Year</option>
                                    <option value="2nd Year" <?= old('level', $course['level'] ?? '') === '2nd Year' ? 'selected' : '' ?>>2nd Year</option>
                                    <option value="3rd Year" <?= old('level', $course['level'] ?? '') === '3rd Year' ? 'selected' : '' ?>>3rd Year</option>
                                    <option value="4th Year" <?= old('level', $course['level'] ?? '') === '4th Year' ? 'selected' : '' ?>>4th Year</option>
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
                                    <option value="active" <?= old('status', $course['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= old('status', $course['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    <option value="completed" <?= old('status', $course['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('status')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('status') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="instructor_id" class="form-label">Assigned Teacher</label>
                            <select class="form-select <?= isset($validation) && $validation->hasError('instructor_id') ? 'is-invalid' : '' ?>" 
                                    id="instructor_id" 
                                    name="instructor_id">
                                <option value="">Not Assigned</option>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?= $teacher['id'] ?>" <?= old('instructor_id', $course['instructor_id'] ?? '') == $teacher['id'] ? 'selected' : '' ?>>
                                        <?= esc($teacher['name']) ?> (<?= esc($teacher['email']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Select a teacher to assign this course to, or leave unassigned</div>
                            <?php if (isset($validation) && $validation->hasError('instructor_id')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('instructor_id') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Course
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

