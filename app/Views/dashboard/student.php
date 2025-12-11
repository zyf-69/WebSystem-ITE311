<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-mortarboard"></i> Student Dashboard</h1>
                <div>
                    <span class="badge bg-primary me-2">Student</span>
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

            <!-- Main Content -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-circle"></i> Welcome, <?= esc($user['name']) ?>!
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Welcome to your Learning Management System dashboard. Here you can access your courses, assignments, and track your progress.</p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6><i class="bi bi-info-circle"></i> Account Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Name:</strong> <?= esc($user['name']) ?></li>
                                        <li><strong>Email:</strong> <?= esc($user['email']) ?></li>
                                        <li><strong>Role:</strong> <span class="badge bg-primary">Student</span></li>
                                        <li><strong>Student ID:</strong> #<?= $user['id'] ?></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="bi bi-lightning-charge"></i> Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <a href="#" class="btn btn-primary">
                                            <i class="bi bi-book"></i> My Courses
                                        </a>
                                        <a href="#" class="btn btn-success">
                                            <i class="bi bi-file-text"></i> My Assignments
                                        </a>
                                        <a href="#" class="btn btn-info">
                                            <i class="bi bi-trophy"></i> My Grades
                                        </a>
                                        <a href="#" class="btn btn-outline-secondary">
                                            <i class="bi bi-gear"></i> Profile Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrolled Courses Section -->
                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-book-check"></i> My Enrolled Courses
                            </h6>
                        </div>
                        <div class="card-body" id="enrolled-courses-section">
                            <?php if (empty($enrolledCourses)): ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">You are not enrolled in any courses yet.</p>
                                    <p class="text-muted small">Browse available courses below to get started!</p>
                                </div>
                            <?php else: ?>
                                <div class="list-group" id="enrolled-courses-list">
                                    <?php foreach ($enrolledCourses as $enrollment): ?>
                                        <div class="list-group-item" data-course-id="<?= $enrollment['course_id'] ?>">
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
                                                </div>
                                                <div>
                                                    <a href="#" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Available Courses Section -->
                    <div class="card mt-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-book-plus"></i> Available Courses
                            </h6>
                        </div>
                        <div class="card-body" id="available-courses-section">
                            <?php if (empty($availableCourses)): ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-check-circle fs-1 text-success"></i>
                                    <p class="text-muted mt-2">You are enrolled in all available courses!</p>
                                </div>
                            <?php else: ?>
                                <div class="list-group" id="available-courses-list">
                                    <?php foreach ($availableCourses as $course): ?>
                                        <div class="list-group-item" data-course-id="<?= $course['id'] ?>">
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
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-bell"></i> Announcements
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">No new announcements.</p>
                        </div>
                    </div>

                    <div class="card mt-3">
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
                                <i class="bi bi-graph-up"></i> My Progress
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">Overall Progress</span>
                                    <span class="small">0%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="small">Completed Courses:</span>
                                <span class="badge bg-success">0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="small">In Progress:</span>
                                <span class="badge bg-primary">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Enrollment Script -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
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
                showAlert('success', response.message);
                
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
                
                // Add course to enrolled courses list
                addToEnrolledList(courseId, $button);
            } else {
                // Show error message
                showAlert('danger', response.message);
                
                // Re-enable button
                $button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
            }
        })
        .fail(function(xhr) {
            var message = 'Failed to enroll. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            
            showAlert('danger', message);
            
            // Re-enable button
            $button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
        });
    });
    
    /**
     * Show Bootstrap alert message
     */
    function showAlert(type, message) {
        var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                       '<i class="bi bi-' + (type === 'success' ? 'check-circle' : 'exclamation-triangle') + '"></i> ' + message +
                       '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                       '</div>';
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top
        $('.container.mt-4').prepend(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }
    
    /**
     * Add course to enrolled courses list
     */
    function addToEnrolledList(courseId, $button) {
        // Get course title from the button's parent item
        var courseTitle = $button.closest('.list-group-item').find('h6').text().trim();
        var courseDescription = $button.closest('.list-group-item').find('p').text().trim();
        
        // Create new enrolled course item
        var enrolledItem = '<div class="list-group-item" data-course-id="' + courseId + '">' +
                          '<div class="d-flex w-100 justify-content-between align-items-start">' +
                          '<div class="flex-grow-1">' +
                          '<h6 class="mb-1">' +
                          '<i class="bi bi-book-fill text-success"></i> ' + courseTitle +
                          '</h6>' +
                          '<p class="mb-1 small text-muted">' + courseDescription + '</p>' +
                          '<small class="text-muted">' +
                          '<i class="bi bi-calendar-check"></i> Enrolled: Just now' +
                          '</small>' +
                          '</div>' +
                          '<div>' +
                          '<a href="#" class="btn btn-sm btn-primary">' +
                          '<i class="bi bi-eye"></i> View' +
                          '</a>' +
                          '</div>' +
                          '</div>' +
                          '</div>';
        
        // Add to enrolled courses list
        var $enrolledList = $('#enrolled-courses-list');
        
        if ($enrolledList.length === 0) {
            // Create the list if it doesn't exist
            $('#enrolled-courses-section').html('<div class="list-group" id="enrolled-courses-list"></div>');
            $enrolledList = $('#enrolled-courses-list');
        }
        
        // Add new item with animation
        $(enrolledItem).hide().prependTo($enrolledList).fadeIn(300);
        
        // Update enrolled courses count in statistics
        updateEnrolledCount();
    }
    
    /**
     * Update enrolled courses count
     */
    function updateEnrolledCount() {
        var count = $('#enrolled-courses-list .list-group-item').length;
        // Update the count in statistics card if it exists
        $('.card-title:contains("Enrolled Courses")').closest('.card-body').find('h2').text(count);
    }
});
</script>
<?= $this->endSection() ?>

