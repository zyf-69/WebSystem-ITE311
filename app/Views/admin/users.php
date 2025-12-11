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

            <!-- Search Box -->
            <div class="card mb-3">
                <div class="card-body">
                    <form id="userSearchForm" class="row g-3">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" 
                                       id="userSearchInput"
                                       class="form-control" 
                                       name="search" 
                                       placeholder="Search users by name or email..." 
                                       value="<?= esc($search ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All Users</h5>
                    <span class="badge bg-light text-dark"><?= count($users) ?> user(s)</span>
                </div>
                <div class="card-body">
                    <?php if (empty($users)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No users found.</p>
                            <?php if (!empty($search)): ?>
                                <a href="<?= base_url('admin/users') ?>" class="btn btn-primary mt-2">
                                    <i class="bi bi-arrow-left"></i> View All Users
                                </a>
                            <?php endif; ?>
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
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <?php foreach ($users as $u): ?>
                                        <?php $isDeleted = !empty($u['deleted_at']); ?>
                                        <tr class="user-row <?= $isDeleted ? 'table-secondary opacity-75' : '' ?>" 
                                            data-user-name="<?= strtolower(esc($u['name'])) ?>" 
                                            data-user-email="<?= strtolower(esc($u['email'])) ?>"
                                            data-user-role="<?= strtolower(esc($u['role'])) ?>">
                                            <td>#<?= $u['id'] ?></td>
                                            <td>
                                                <?= esc($u['name']) ?>
                                                <?php if ($u['id'] == $user['id']): ?>
                                                    <span class="badge bg-info ms-1">You</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($u['email']) ?></td>
                                            <td>
                                                <?php if ($u['role'] === 'admin'): ?>
                                                    <span class="badge bg-danger">Administrator</span>
                                                <?php elseif ($u['role'] === 'teacher'): ?>
                                                    <span class="badge bg-success">Teacher</span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary">Student</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($isDeleted): ?>
                                                    <span class="badge bg-dark">
                                                        <i class="bi bi-x-circle"></i> MARKED AS DELETED
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        Deleted: <?= date('M d, Y', strtotime($u['deleted_at'])) ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Active
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                                            <td>
                                                <?php if ($u['id'] != $user['id']): ?>
                                                    <?php if ($isDeleted): ?>
                                                        <!-- Recover Deleted User -->
                                                        <a href="<?= base_url('admin/recover-user/' . $u['id']) ?>" 
                                                           class="btn btn-success btn-sm"
                                                           onclick="return confirm('Are you sure you want to recover this user?')">
                                                            <i class="bi bi-arrow-counterclockwise"></i> Recover Account
                                                        </a>
                                                    <?php else: ?>
                                                        <!-- Active User Management -->
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                                <i class="bi bi-gear"></i> Manage
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href="<?= base_url('admin/edit-user/' . $u['id']) ?>" class="dropdown-item">
                                                                        <i class="bi bi-pencil"></i> Edit User
                                                                    </a>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><h6 class="dropdown-header">Change Role</h6></li>
                                                                <?php if ($u['role'] !== 'admin'): ?>
                                                                    <li>
                                                                        <form method="post" action="<?= base_url('admin/update-role/' . $u['id']) ?>" class="d-inline">
                                                                            <input type="hidden" name="role" value="admin">
                                                                            <button type="submit" class="dropdown-item">
                                                                                <i class="bi bi-shield-check"></i> Change to Administrator
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <?php if ($u['role'] !== 'teacher'): ?>
                                                                    <li>
                                                                        <form method="post" action="<?= base_url('admin/update-role/' . $u['id']) ?>" class="d-inline">
                                                                            <input type="hidden" name="role" value="teacher">
                                                                            <button type="submit" class="dropdown-item">
                                                                                <i class="bi bi-person-badge"></i> Change to Teacher
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <?php if ($u['role'] !== 'student'): ?>
                                                                    <li>
                                                                        <form method="post" action="<?= base_url('admin/update-role/' . $u['id']) ?>" class="d-inline">
                                                                            <input type="hidden" name="role" value="student">
                                                                            <button type="submit" class="dropdown-item">
                                                                                <i class="bi bi-mortarboard"></i> Change to Student
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <a href="<?= base_url('admin/delete-user/' . $u['id']) ?>" 
                                                                       class="dropdown-item text-danger"
                                                                       onclick="return confirm('Are you sure you want to mark this user as deleted? The user will not be permanently removed from the database.')">
                                                                        <i class="bi bi-trash"></i> Mark as Deleted
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
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

<!-- User Search Script -->
<script>
// Ensure this runs after jQuery and DOM are ready
if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function($) {
        // Auto-filtering as user types (Instant Search)
        function filterUsers() {
            var searchValue = $('#userSearchInput').val().toLowerCase().trim();
            
            // Remove previous no results message
            $('#usersNoResultsMessage').remove();
            
            // Filter user rows
            var hasVisibleRows = false;
            $('.user-row').each(function() {
                var $row = $(this);
                var userName = $row.data('user-name') || '';
                var userEmail = $row.data('user-email') || '';
                var userRole = $row.data('user-role') || '';
                
                // Search in name, email, and role
                var match = userName.indexOf(searchValue) > -1 || 
                           userEmail.indexOf(searchValue) > -1 || 
                           userRole.indexOf(searchValue) > -1;
                
                if (match) {
                    $row.show();
                    hasVisibleRows = true;
                } else {
                    $row.hide();
                }
            });
            
            // Show message if no results and search has value
            var $tbody = $('#usersTableBody');
            if (searchValue !== '' && !hasVisibleRows) {
                if ($tbody.find('#usersNoResultsMessage').length === 0) {
                    $tbody.append(
                        '<tr id="usersNoResultsMessage">' +
                        '<td colspan="7" class="text-center py-4">' +
                        '<div class="alert alert-info mb-0">' +
                        '<i class="bi bi-info-circle"></i> No users found matching your search.' +
                        '</div>' +
                        '</td>' +
                        '</tr>'
                    );
                }
            }
        }
        
        // Bind events for auto-filtering using event delegation
        $(document).on('keyup input paste', '#userSearchInput', filterUsers);
        
        // Prevent form submission - just use auto-filtering
        $(document).on('submit', '#userSearchForm', function(e) {
            e.preventDefault();
            filterUsers();
            return false;
        });
    });
} else {
    // Fallback if jQuery loads later
    window.addEventListener('load', function() {
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                function filterUsers() {
                    var searchValue = $('#userSearchInput').val().toLowerCase().trim();
                    $('#usersNoResultsMessage').remove();
                    var hasVisibleRows = false;
                    $('.user-row').each(function() {
                        var $row = $(this);
                        var userName = $row.data('user-name') || '';
                        var userEmail = $row.data('user-email') || '';
                        var userRole = $row.data('user-role') || '';
                        var match = userName.indexOf(searchValue) > -1 || 
                                   userEmail.indexOf(searchValue) > -1 || 
                                   userRole.indexOf(searchValue) > -1;
                        if (match) {
                            $row.show();
                            hasVisibleRows = true;
                        } else {
                            $row.hide();
                        }
                    });
                    var $tbody = $('#usersTableBody');
                    if (searchValue !== '' && !hasVisibleRows) {
                        if ($tbody.find('#usersNoResultsMessage').length === 0) {
                            $tbody.append(
                                '<tr id="usersNoResultsMessage">' +
                                '<td colspan="7" class="text-center py-4">' +
                                '<div class="alert alert-info mb-0">' +
                                '<i class="bi bi-info-circle"></i> No users found matching your search.' +
                                '</div>' +
                                '</td>' +
                                '</tr>'
                            );
                        }
                    }
                }
                $(document).on('keyup input paste', '#userSearchInput', filterUsers);
                $(document).on('submit', '#userSearchForm', function(e) {
                    e.preventDefault();
                    filterUsers();
                    return false;
                });
            });
        }
    });
}
</script>
<?= $this->endSection() ?>
