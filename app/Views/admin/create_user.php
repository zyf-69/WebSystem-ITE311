<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-person-plus"></i> Create New User</h1>
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
                    <form method="post" action="<?= base_url('admin/create-user') ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" 
                                   class="form-control <?= isset($validation) && $validation->hasError('name') ? 'is-invalid' : '' ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?= old('name') ?>" 
                                   required>
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
                                   value="<?= old('email') ?>" 
                                   required>
                            <?php if (isset($validation) && $validation->hasError('email')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('email') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <div class="form-text">Password must be at least 6 characters long.</div>
                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('password') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">User Role</label>
                            <select class="form-select <?= isset($validation) && $validation->hasError('role') ? 'is-invalid' : '' ?>" 
                                    id="role" 
                                    name="role" 
                                    required>
                                <option value="">Select Role</option>
                                <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Student</option>
                                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Administrator</option>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('role')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('role') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create User
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
