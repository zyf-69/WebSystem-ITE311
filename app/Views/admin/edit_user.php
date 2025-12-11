<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-pencil"></i> Edit User</h1>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Users
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
                    <h5 class="card-title mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('admin/edit-user/' . $userToEdit['id']) ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" 
                                   class="form-control <?= isset($validation) && $validation->hasError('name') ? 'is-invalid' : '' ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?= old('name', $userToEdit['name']) ?>" 
                                   pattern="[a-zA-Z\s\-\']+"
                                   placeholder="Enter full name (letters, spaces, hyphens, apostrophes only)"
                                   required>
                            <div class="form-text">Only letters, spaces, hyphens (-), and apostrophes (') are allowed. No special characters.</div>
                            <?php if (isset($validation) && $validation->hasError('name')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                                   id="email" 
                                   name="email" 
                                   value="<?= old('email', $userToEdit['email']) ?>" 
                                   pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                   placeholder="Enter email (e.g., example@gmail.com)"
                                   required>
                            <div class="form-text">Only letters, numbers, dots (.), underscores (_), hyphens (-), and @ symbol are allowed.</div>
                            <?php if (isset($validation) && $validation->hasError('email')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('email') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Role</label>
                            <div>
                                <?php if ($userToEdit['role'] === 'admin'): ?>
                                    <span class="badge bg-danger">Administrator</span>
                                <?php elseif ($userToEdit['role'] === 'teacher'): ?>
                                    <span class="badge bg-success">Teacher</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Student</span>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">To change role, use the "Change Role" option in the users list.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update User
                            </button>
                            <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary">
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

