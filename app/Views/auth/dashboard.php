<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
                <div>
                    <?php if ($user['role'] === 'admin'): ?>
                        <span class="badge bg-danger me-2">Administrator</span>
                    <?php elseif ($user['role'] === 'teacher'): ?>
                        <span class="badge bg-success me-2">Teacher</span>
                    <?php else: ?>
                        <span class="badge bg-primary me-2">Student</span>
                    <?php endif; ?>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-box-arrow-right"></i> Logout
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

            <!-- Role-based Statistics Cards -->
            <?php if ($user['role'] === 'admin'): ?>
                <!-- Admin Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Total Users</h6>
                                        <h2 class="mt-2"><?= $roleData['total_users'] ?></h2>
                                    </div>
                                    <i class="bi bi-people fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Administrators</h6>
                                        <h2 class="mt-2"><?= $roleData['admin_count'] ?></h2>
                                    </div>
                                    <i class="bi bi-shield-check fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Teachers</h6>
                                        <h2 class="mt-2"><?= $roleData['teacher_count'] ?></h2>
                                    </div>
                                    <i class="bi bi-person-badge fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Students</h6>
                                        <h2 class="mt-2"><?= $roleData['student_count'] ?></h2>
                                    </div>
                                    <i class="bi bi-mortarboard fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($user['role'] === 'teacher'): ?>
                <!-- Teacher Statistics -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">My Courses</h6>
                                        <h2 class="mt-2"><?= count($roleData['courses']) ?></h2>
                                    </div>
                                    <i class="bi bi-book fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">My Students</h6>
                                        <h2 class="mt-2"><?= $roleData['students'] ?></h2>
                                    </div>
                                    <i class="bi bi-people fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Pending Requests</h6>
                                        <h2 class="mt-2"><?= $roleData['pending_count'] ?? 0 ?></h2>
                                    </div>
                                    <i class="bi bi-hourglass-split fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Enrollment Notification for Teacher -->
                <?php if (isset($roleData['pending_enrollments']) && !empty($roleData['pending_enrollments'])): ?>
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <h5 class="alert-heading">
                            <i class="bi bi-exclamation-triangle"></i> 
                            You have <?= count($roleData['pending_enrollments']) ?> pending enrollment request(s)!
                        </h5>
                        <p class="mb-2">Students are waiting for your approval to enroll in your courses.</p>
                        <hr>
                        <div class="mb-2">
                            <?php foreach (array_slice($roleData['pending_enrollments'], 0, 3) as $enrollment): ?>
                                <div class="mb-1">
                                    <strong><?= esc($enrollment['student_name'] ?? 'Unknown Student') ?></strong> 
                                    wants to enroll in <strong><?= esc($enrollment['course_title'] ?? 'Course') ?></strong>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($roleData['pending_enrollments']) > 3): ?>
                                <div class="text-muted">... and <?= count($roleData['pending_enrollments']) - 3 ?> more</div>
                            <?php endif; ?>
                        </div>
                        <a href="<?= base_url('my-students') ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-people"></i> Review Enrollment Requests
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Student Statistics -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Enrolled Courses</h6>
                                        <h2 class="mt-2"><?= count($roleData['enrolled_courses']) ?></h2>
                                    </div>
                                    <i class="bi bi-book fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Assignments</h6>
                                        <h2 class="mt-2"><?= count($roleData['assignments']) ?></h2>
                                    </div>
                                    <i class="bi bi-file-text fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Main Content -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'success' : 'primary') ?> text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-circle"></i> Welcome, <?= esc($user['name']) ?>!
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($user['role'] === 'admin'): ?>
                                <p class="card-text">You are logged in as an administrator. You have full access to manage the Learning Management System.</p>
                            <?php elseif ($user['role'] === 'teacher'): ?>
                                <p class="card-text">You are logged in as a teacher. You can manage your courses, students, and assignments.</p>
                            <?php else: ?>
                                <p class="card-text">You are logged in as a student. Access your courses, assignments, and learning materials.</p>
                            <?php endif; ?>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6><i class="bi bi-info-circle"></i> Account Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Name:</strong> <?= esc($user['name']) ?></li>
                                        <li><strong>Email:</strong> <?= esc($user['email']) ?></li>
                                        <li><strong>Role:</strong> 
                                            <?php if ($user['role'] === 'admin'): ?>
                                                <span class="badge bg-danger">Administrator</span>
                                            <?php elseif ($user['role'] === 'teacher'): ?>
                                                <span class="badge bg-success">Teacher</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary">Student</span>
                                            <?php endif; ?>
                                        </li>
                                        <li><strong>User ID:</strong> #<?= $user['id'] ?></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="bi bi-lightning-charge"></i> Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <a href="<?= base_url('admin/users') ?>" class="btn btn-primary">
                                                <i class="bi bi-people"></i> Manage Users
                                            </a>
                                            <a href="<?= base_url('admin/create-user') ?>" class="btn btn-success">
                                                <i class="bi bi-person-plus"></i> Create New User
                                            </a>
                                            <a href="<?= base_url('admin/create-course') ?>" class="btn btn-info">
                                                <i class="bi bi-plus-circle"></i> Add New Course
                                            </a>
                                            <a href="<?= base_url('admin/assign-course') ?>" class="btn btn-warning">
                                                <i class="bi bi-person-check"></i> Assign Course to Teacher
                                            </a>
                                            <a href="<?= base_url('announcements') ?>" class="btn btn-outline-primary">
                                                <i class="bi bi-megaphone"></i> View Announcements
                                            </a>
                                        <?php elseif ($user['role'] === 'teacher'): ?>
                                            <a href="<?= base_url('announcements') ?>" class="btn btn-primary">
                                                <i class="bi bi-megaphone"></i> View Announcements
                                            </a>
                                            <a href="<?= base_url('my-course') ?>" class="btn btn-outline-primary">
                                                <i class="bi bi-book"></i> My Courses
                                            </a>
                                            <a href="<?= base_url('my-students') ?>" class="btn btn-outline-primary">
                                                <i class="bi bi-people"></i> My Students
                                                <?php if (isset($roleData['pending_count']) && $roleData['pending_count'] > 0): ?>
                                                    <span class="badge bg-warning text-dark ms-1"><?= $roleData['pending_count'] ?></span>
                                                <?php endif; ?>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('announcements') ?>" class="btn btn-primary">
                                                <i class="bi bi-megaphone"></i> View Announcements
                                            </a>
                                            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-primary">
                                                <i class="bi bi-book"></i> My Courses
                                            </a>
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="bi bi-file-text"></i> My Assignments
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-clock-history"></i> Recent Activity
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">No recent activity to display.</p>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-hdd-network"></i> System Status
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-database"></i> Database:</span>
                                <span class="badge bg-success">Connected</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-key"></i> Session:</span>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="bi bi-clock"></i> Last Login:</span>
                                <span class="text-muted small">Just now</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Student Enrollment Sections -->
            <?php if ($user['role'] === 'student' && isset($roleData['enrolled_courses'])): ?>
                <div class="row mt-4">
                    <!-- Pending Enrollment Requests -->
                    <?php if (!empty($roleData['pending_enrollments'])): ?>
                        <div class="col-12 mb-3">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-hourglass-split"></i> Pending Enrollment Requests
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <?php foreach ($roleData['pending_enrollments'] as $enrollment): ?>
                                            <div class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">
                                                            <i class="bi bi-clock-history text-warning"></i>
                                                            <?= esc($enrollment['title'] ?? 'Course #' . $enrollment['course_id']) ?>
                                                        </h6>
                                                        <p class="mb-1 small text-muted">
                                                            <?= esc($enrollment['description'] ?? 'No description available') ?>
                                                        </p>
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar"></i> 
                                                            Requested: <?= date('M d, Y', strtotime($enrollment['enrollment_date'] ?? $enrollment['created_at'])) ?>
                                                        </small>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-warning">Pending Approval</span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- My Enrolled Courses -->
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-book-check"></i> My Enrolled Courses
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($roleData['enrolled_courses'])): ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-book fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No enrolled courses yet.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($roleData['enrolled_courses'] as $enrollment): ?>
                                            <div class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">
                                                            <i class="bi bi-book-fill text-success"></i>
                                                            <?= esc($enrollment['title'] ?? 'Course #' . $enrollment['course_id']) ?>
                                                        </h6>
                                                        <p class="mb-1 small text-muted">
                                                            <?= esc($enrollment['description'] ?? 'No description available') ?>
                                                        </p>
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar-check"></i> 
                                                            Enrolled: <?= date('M d, Y', strtotime($enrollment['enrollment_date'] ?? $enrollment['created_at'])) ?>
                                                        </small>
                                                        <div class="mt-2">
                                                            <strong>Materials:</strong>
                                                            <?php if (empty($enrollment['materials'])): ?>
                                                                <div class="text-muted small">No materials uploaded yet.</div>
                                                            <?php else: ?>
                                                                <ul class="list-unstyled mb-0 small">
                                                                    <?php foreach ($enrollment['materials'] as $material): ?>
                                                                        <li class="d-flex align-items-center mb-1">
                                                                            <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                                                            <span class="flex-grow-1"><?= esc($material['file_name']) ?></span>
                                                                            <a class="btn btn-sm btn-outline-primary" href="<?= base_url('materials/download/' . $material['id']) ?>">
                                                                                <i class="bi bi-download"></i> Download
                                                                            </a>
                                                                        </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-success">Enrolled</span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Available Courses -->
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-book-plus"></i> Available Courses
                                </h5>
                            </div>
                            <div class="card-body" id="available-courses-section">
                                <!-- Search Form -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <form id="searchForm" class="d-flex">
                                            <div class="input-group">
                                                <input type="text" 
                                                       id="searchInput" 
                                                       class="form-control" 
                                                       placeholder="Search courses..." 
                                                       name="search_term">
                                                <button class="btn btn-outline-primary" type="submit">
                                                    <i class="bi bi-search"></i> Search
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <?php if (empty($roleData['available_courses'])): ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-check-circle fs-1 text-success"></i>
                                        <p class="text-muted mt-2">You are enrolled in all available courses!</p>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group" id="available-courses-list">
                                        <?php foreach ($roleData['available_courses'] as $course): ?>
                                            <div class="list-group-item course-card" data-course-id="<?= $course['id'] ?>">
                                                <div class="d-flex w-100 justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">
                                                            <i class="bi bi-book text-primary"></i>
                                                            <?= esc($course['title']) ?>
                                                        </h6>
                                                        <p class="mb-1 small text-muted">
                                                            <?= esc($course['description'] ?? 'No description available') ?>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-success enroll-btn" 
                                                                data-course-id="<?= $course['id'] ?>">
                                                            <i class="bi bi-plus-circle"></i> Enroll
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- AJAX Enrollment Script -->
                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                <script>
                $(document).ready(function() {
                    // Client-side filtering (Instant Search)
                    $('#searchInput').on('keyup', function() {
                        var value = $(this).val().toLowerCase();
                        $('.course-card').filter(function() {
                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                        });
                    });
                    
                    // Server-side search with AJAX
                    $('#searchForm').on('submit', function(e) {
                        e.preventDefault();
                        var searchTerm = $('#searchInput').val();
                        
                        $.get('<?= base_url('courses/search') ?>', {search_term: searchTerm}, function(data) {
                            $('#available-courses-list').empty();
                            
                            if (data.length > 0) {
                                $.each(data, function(index, course) {
                                    var courseHtml = '<div class="list-group-item course-card" data-course-id="' + course.id + '">' +
                                        '<div class="d-flex w-100 justify-content-between align-items-start">' +
                                        '<div class="flex-grow-1">' +
                                        '<h6 class="mb-1">' +
                                        '<i class="bi bi-book text-primary"></i> ' + (course.title || 'Course #' + course.id) +
                                        '</h6>' +
                                        '<p class="mb-1 small text-muted">' + (course.description || 'No description available') + '</p>' +
                                        '</div>' +
                                        '<div>' +
                                        '<button type="button" class="btn btn-sm btn-success enroll-btn" data-course-id="' + course.id + '">' +
                                        '<i class="bi bi-plus-circle"></i> Enroll' +
                                        '</button>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                    $('#available-courses-list').append(courseHtml);
                                });
                            } else {
                                $('#available-courses-list').html('<div class="col-12"><div class="alert alert-info">No courses found matching your search.</div></div>');
                            }
                        }).fail(function() {
                            $('#available-courses-list').html('<div class="col-12"><div class="alert alert-danger">Error performing search. Please try again.</div></div>');
                        });
                    });
                    
                    // Listen for click on Enroll button
                    $(document).on('click', '.enroll-btn', function(e) {
                        e.preventDefault();
                        
                        var $button = $(this);
                        var courseId = $button.data('course-id');
                        var $courseItem = $button.closest('.list-group-item');
                        
                        // Disable button to prevent multiple clicks
                        $button.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Enrolling...');
                        
                        // Send AJAX request
                        $.post('<?= base_url('course/enroll') ?>', {
                            course_id: courseId
                        })
                        .done(function(response) {
                            if (response.success) {
                                // Show success message
                                var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                    '<i class="bi bi-check-circle"></i> ' + response.message +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                                    '</div>';
                                $('body').prepend(alertHtml);
                                
                                // Remove course from available courses list
                                $courseItem.fadeOut(300, function() {
                                    $(this).remove();
                                    
                                    // Check if no more available courses
                                    if ($('#available-courses-list .list-group-item').length === 0) {
                                        $('#available-courses-section').html(
                                            '<div class="text-center py-4">' +
                                            '<i class="bi bi-check-circle fs-1 text-success"></i>' +
                                            '<p class="text-muted mt-2">You are enrolled in all available courses!</p>' +
                                            '</div>'
                                        );
                                    }
                                });
                                
                                // Reload page after 2 seconds to show pending enrollment
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                // Show error message
                                var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                    '<i class="bi bi-exclamation-triangle"></i> ' + response.message +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                                    '</div>';
                                $('body').prepend(alertHtml);
                                
                                // Re-enable button
                                $button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
                            }
                        })
                        .fail(function(xhr) {
                            var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                '<i class="bi bi-exclamation-triangle"></i> An error occurred. Please try again.' +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                                '</div>';
                            $('body').prepend(alertHtml);
                            
                            // Re-enable button
                            $button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
                        });
                    });
                });
                </script>
            <?php endif; ?>
            
            <!-- Course Management Table for Admin -->
            <?php if ($user['role'] === 'admin' && isset($roleData['courses'])): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-book"></i> Course Management
                                </h5>
                                <span class="badge bg-light text-dark"><?= count($roleData['courses']) ?> course(s)</span>
                            </div>
                            <div class="card-body">
                                <?php if (empty($roleData['courses'])): ?>
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i> No courses found. <a href="<?= base_url('admin/create-course') ?>" class="alert-link">Create your first course</a>.
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Course Title</th>
                                                    <th>Instructor</th>
                                                    <th>Year Level</th>
                                                    <th>Schedule</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($roleData['courses'] as $course): ?>
                                                    <tr>
                                                        <td>#<?= esc($course['id']) ?></td>
                                                        <td>
                                                            <strong><?= esc($course['title']) ?></strong>
                                                            <?php if (!empty($course['description'])): ?>
                                                                <br><small class="text-muted"><?= esc(substr($course['description'], 0, 50)) ?><?= strlen($course['description']) > 50 ? '...' : '' ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($course['instructor_name'])): ?>
                                                                <span class="badge bg-success"><?= esc($course['instructor_name']) ?></span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Not Assigned</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($course['level'])): ?>
                                                                <span class="badge bg-info"><?= esc($course['level']) ?></span>
                                                            <?php else: ?>
                                                                <span class="text-muted">N/A</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($course['start_time']) && !empty($course['end_time'])): ?>
                                                                <i class="bi bi-clock"></i>
                                                                <?= date('h:i A', strtotime($course['start_time'])) ?> - 
                                                                <?= date('h:i A', strtotime($course['end_time'])) ?>
                                                                <?php if (!empty($course['schedule_days'])): ?>
                                                                    <br><small class="text-muted"><?= esc($course['schedule_days']) ?></small>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <span class="text-muted">Not Scheduled</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            $status = $course['status'] ?? 'active';
                                                            $statusClass = $status === 'active' ? 'success' : ($status === 'inactive' ? 'secondary' : 'warning');
                                                            ?>
                                                            <span class="badge bg-<?= $statusClass ?>"><?= ucfirst(esc($status)) ?></span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <a href="<?= base_url('admin/edit-course/' . $course['id']) ?>" class="btn btn-sm btn-primary" title="Edit Course">
                                                                    <i class="bi bi-pencil"></i>
                                                                </a>
                                                                <a href="<?= base_url('course/' . $course['id'] . '/upload') ?>" class="btn btn-sm btn-warning" title="Attach File to Course">
                                                                    <i class="bi bi-upload"></i>
                                                                </a>
                                                                <a href="<?= base_url('admin/delete-course/' . $course['id']) ?>" 
                                                                   class="btn btn-sm btn-danger" 
                                                                   title="Delete Course"
                                                                   onclick="return confirm('Are you sure you want to delete this course?');">
                                                                    <i class="bi bi-trash"></i>
                                                                </a>
                                                            </div>
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
            <?php endif; ?>
        </div>
    </div>
</div>


<?= $this->endSection() ?>
