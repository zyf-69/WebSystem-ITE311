<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-people"></i> User Management</h1>
                <div>
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                    <a href="<?= base_url('admin/create-user') ?>" class="btn btn-success">
                        <i class="bi bi-person-plus"></i> Create New User
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
                    <h5 class="card-title mb-0">All Users</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($users)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No users found.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $u): ?>
                                        <tr>
                                            <td>#<?= $u['id'] ?></td>
                                            <td><?= esc($u['name']) ?></td>
                                            <td><?= esc($u['email']) ?></td>
                                            <td>
                                                <?php if ($u['role'] === 'admin'): ?>
                                                    <span class="badge bg-danger">Administrator</span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary">Student</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                                            <td>
                                                <?php if ($u['id'] != $user['id']): ?>
                                                    <!-- Role Change Dropdown -->
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                            <i class="bi bi-gear"></i> Manage
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form method="post" action="<?= base_url('admin/update-role/' . $u['id']) ?>" class="d-inline">
                                                                    <input type="hidden" name="role" value="<?= $u['role'] === 'admin' ? 'student' : 'admin' ?>">
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="bi bi-arrow-repeat"></i> 
                                                                        Change to <?= $u['role'] === 'admin' ? 'Student' : 'Admin' ?>
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a href="<?= base_url('admin/delete-user/' . $u['id']) ?>" 
                                                                   class="dropdown-item text-danger"
                                                                   onclick="return confirm('Are you sure you want to delete this user?')">
                                                                    <i class="bi bi-trash"></i> Delete User
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Current User</span>
                                                <?php endif; ?>
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
    </div>
</div>
<?= $this->endSection() ?>
