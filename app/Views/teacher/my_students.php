<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-people"></i> My Students</h1>
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

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h6 class="card-title">Total Students</h6>
                            <h2><?= $totalStudents ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h6 class="card-title">Pending Requests</h6>
                            <h2><?= $pendingCount ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h6 class="card-title">Enrolled Students</h6>
                            <h2><?= $enrolledCount ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (empty($courseEnrollments)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No courses assigned to you yet. Please contact an administrator.
                </div>
            <?php else: ?>
                <?php foreach ($courseEnrollments as $courseData): ?>
                    <?php 
                    $courseId = $courseData['course']['id'];
                    $courseTitleSlug = preg_replace('/[^a-z0-9]+/', '-', strtolower(esc($courseData['course']['title'])));
                    ?>
                    <div class="card mb-4 course-section" data-course-title="<?= strtolower(esc($courseData['course']['title'])) ?>" data-course-id="<?= $courseId ?>">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-book"></i> <?= esc($courseData['course']['title']) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Course-specific Search Form -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form class="course-search-form d-flex" data-course-id="<?= $courseId ?>">
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control course-search-input" 
                                                   data-course-id="<?= $courseId ?>"
                                                   placeholder="Search students in this course..." 
                                                   name="search_term">
                                            <button class="btn btn-outline-primary" type="submit">
                                                <i class="bi bi-search"></i> Search
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <?php if (empty($courseData['enrollments'])): ?>
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> No students enrolled in this course yet.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Enrollment Date</th>
                                                <th>Actions / Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody class="course-students-tbody" data-course-id="<?= $courseId ?>">
                                            <?php foreach ($courseData['enrollments'] as $enrollment): ?>
                                                <tr class="student-row course-<?= $courseId ?>-student" data-student-name="<?= strtolower(esc($enrollment['student_name'] ?? 'Unknown')) ?>" data-student-email="<?= strtolower(esc($enrollment['student_email'] ?? 'N/A')) ?>">
                                                    <td><?= esc($enrollment['student_name'] ?? 'Unknown') ?></td>
                                                    <td><?= esc($enrollment['student_email'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <?php 
                                                        $status = $enrollment['status'] ?? 'pending';
                                                        $statusClass = $status === 'enrolled' ? 'success' : ($status === 'pending' ? 'warning' : 'danger');
                                                        ?>
                                                        <span class="badge bg-<?= $statusClass ?>">
                                                            <?= ucfirst(esc($status)) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?= date('M d, Y', strtotime($enrollment['enrollment_date'] ?? $enrollment['created_at'])) ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($status === 'pending'): ?>
                                                            <a href="<?= base_url('teacher/accept-enrollment/' . $enrollment['id']) ?>" 
                                                               class="btn btn-sm btn-success"
                                                               onclick="return confirm('Accept this enrollment request?');">
                                                                <i class="bi bi-check-circle"></i> Accept
                                                            </a>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#declineModal<?= $enrollment['id'] ?>">
                                                                <i class="bi bi-x-circle"></i> Decline
                                                            </button>
                                                        <?php elseif ($status === 'enrolled'): ?>
                                                            <a href="<?= base_url('teacher/unenroll-student/' . $enrollment['id']) ?>" 
                                                               class="btn btn-sm btn-danger"
                                                               onclick="return confirm('Are you sure you want to unenroll this student?');">
                                                                <i class="bi bi-person-x"></i> Unenroll
                                                            </a>
                                                        <?php elseif ($status === 'declined' && !empty($enrollment['decline_reason'])): ?>
                                                            <small class="text-muted">
                                                                <i class="bi bi-info-circle"></i> Reason: <?= esc($enrollment['decline_reason']) ?>
                                                            </small>
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
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- All Students Section - For Enrolling Students -->
            <?php if (!empty($myCourses) && !empty($allStudents)): ?>
                <div class="card mt-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-plus"></i> Enroll Students to Your Courses
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Select a course and enroll students directly. Students will be notified automatically.</p>
                        
                        <!-- Enroll Students Search Form -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form id="enrollStudentsSearchForm" class="d-flex">
                                    <div class="input-group">
                                        <input type="text" 
                                               id="enrollStudentsSearchInput" 
                                               class="form-control" 
                                               placeholder="Search students..." 
                                               name="search_term">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="bi bi-search"></i> Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                        <?php foreach ($myCourses as $course): ?>
                                            <th><?= esc($course['title']) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody id="enrollStudentsTableBody">
                                    <?php foreach ($allStudents as $student): ?>
                                        <tr class="enroll-student-row" data-student-name="<?= strtolower(esc($student['name'])) ?>" data-student-email="<?= strtolower(esc($student['email'])) ?>">
                                            <td><?= esc($student['name']) ?></td>
                                            <td><?= esc($student['email']) ?></td>
                                            <?php foreach ($myCourses as $course): ?>
                                                <td>
                                                    <?php 
                                                    $enrollmentStatus = $student['enrollments'][$course['id']] ?? null;
                                                    if ($enrollmentStatus === 'enrolled'): ?>
                                                        <span class="badge bg-success">Enrolled</span>
                                                    <?php elseif ($enrollmentStatus === 'pending'): ?>
                                                        <span class="badge bg-warning">Pending</span>
                                                    <?php elseif ($enrollmentStatus === 'declined'): ?>
                                                        <form action="<?= base_url('teacher/enroll-student') ?>" method="post" class="d-inline">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Enroll <?= esc($student['name']) ?> in <?= esc($course['title']) ?>?')">
                                                                <i class="bi bi-person-plus"></i> Re-enroll
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <form action="<?= base_url('teacher/enroll-student') ?>" method="post" class="d-inline">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Enroll <?= esc($student['name']) ?> in <?= esc($course['title']) ?>?')">
                                                                <i class="bi bi-person-plus"></i> Enroll
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Decline Enrollment Modals -->
<?php foreach ($courseEnrollments as $courseData): ?>
    <?php foreach ($courseData['enrollments'] as $enrollment): ?>
        <?php if (($enrollment['status'] ?? 'pending') === 'pending'): ?>
            <div class="modal fade" id="declineModal<?= $enrollment['id'] ?>" tabindex="-1" aria-labelledby="declineModalLabel<?= $enrollment['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('teacher/decline-enrollment/' . $enrollment['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="modal-header">
                                <h5 class="modal-title" id="declineModalLabel<?= $enrollment['id'] ?>">
                                    <i class="bi bi-x-circle text-danger"></i> Decline Enrollment Request
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to decline the enrollment request from <strong><?= esc($enrollment['student_name'] ?? 'Unknown') ?></strong> for course <strong><?= esc($courseData['course']['title']) ?></strong>?</p>
                                <div class="mb-3">
                                    <label for="decline_reason<?= $enrollment['id'] ?>" class="form-label">
                                        Reason for Decline <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" 
                                              id="decline_reason<?= $enrollment['id'] ?>" 
                                              name="decline_reason" 
                                              rows="3" 
                                              required 
                                              placeholder="Please provide a reason for declining this enrollment request..."></textarea>
                                    <div class="form-text">This reason will be visible to the student.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle"></i> Decline Enrollment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endforeach; ?>

<!-- Search Script -->
<script>
// Ensure this runs after jQuery and DOM are ready
if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function($) {
        // ===== COURSE-SPECIFIC SEARCH (Individual course sections) =====
        function filterCourseStudents(courseId) {
            var $input = $('.course-search-input[data-course-id="' + courseId + '"]');
            var searchValue = $input.val().toLowerCase().trim();
            
            // Remove previous no results message for this course
            $('.course-' + courseId + '-no-results').remove();
            
            // Filter students in this specific course
            var hasVisibleRows = false;
            $('.course-' + courseId + '-student').each(function() {
                var $row = $(this);
                var studentName = $row.data('student-name') || '';
                var studentEmail = $row.data('student-email') || '';
                var match = studentName.indexOf(searchValue) > -1 || studentEmail.indexOf(searchValue) > -1;
                
                if (match) {
                    $row.show();
                    hasVisibleRows = true;
                } else {
                    $row.hide();
                }
            });
            
            // Show message if no results
            var $tbody = $('.course-students-tbody[data-course-id="' + courseId + '"]');
            if (searchValue !== '' && !hasVisibleRows) {
                if ($tbody.find('.course-' + courseId + '-no-results').length === 0) {
                    $tbody.append(
                        '<tr class="course-' + courseId + '-no-results">' +
                        '<td colspan="5" class="text-center py-3">' +
                        '<div class="alert alert-info mb-0">' +
                        '<i class="bi bi-info-circle"></i> No students found matching your search.' +
                        '</div>' +
                        '</td>' +
                        '</tr>'
                    );
                }
            }
        }
        
        // ===== ENROLL STUDENTS SEARCH =====
        function filterEnrollStudents() {
            var searchValue = $('#enrollStudentsSearchInput').val().toLowerCase().trim();
            
            // Remove previous no results message
            $('#enrollStudentsNoResults').remove();
            
            // Filter enroll student rows
            var hasVisibleRows = false;
            $('.enroll-student-row').each(function() {
                var $row = $(this);
                var studentName = $row.data('student-name') || '';
                var studentEmail = $row.data('student-email') || '';
                var match = studentName.indexOf(searchValue) > -1 || studentEmail.indexOf(searchValue) > -1;
                
                if (match) {
                    $row.show();
                    hasVisibleRows = true;
                } else {
                    $row.hide();
                }
            });
            
            // Show message if no results
            var $tbody = $('#enrollStudentsTableBody');
            if (searchValue !== '' && !hasVisibleRows) {
                if ($tbody.find('#enrollStudentsNoResults').length === 0) {
                    var colspan = $tbody.find('tr:first td').length || 2;
                    $tbody.append(
                        '<tr id="enrollStudentsNoResults">' +
                        '<td colspan="' + colspan + '" class="text-center py-3">' +
                        '<div class="alert alert-info mb-0">' +
                        '<i class="bi bi-info-circle"></i> No students found matching your search.' +
                        '</div>' +
                        '</td>' +
                        '</tr>'
                    );
                }
            }
        }
        
        // Bind events for course-specific searches using event delegation
        $(document).on('keyup input paste', '.course-search-input', function() {
            var courseId = $(this).data('course-id');
            filterCourseStudents(courseId);
        });
        $(document).on('submit', '.course-search-form', function(e) {
            e.preventDefault();
            var courseId = $(this).data('course-id');
            filterCourseStudents(courseId);
            return false;
        });
        
        // Bind events for enroll students search using event delegation
        $(document).on('keyup input paste', '#enrollStudentsSearchInput', filterEnrollStudents);
        $(document).on('submit', '#enrollStudentsSearchForm', function(e) {
            e.preventDefault();
            filterEnrollStudents();
            return false;
        });
    });
} else {
    // Fallback if jQuery loads later
    window.addEventListener('load', function() {
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                // Same implementation as above
                function filterTeacherStudents() {
                    var searchValue = $('#searchInput').val().toLowerCase().trim();
                    $('.student-row').each(function() {
                        var $row = $(this);
                        var studentName = $row.data('student-name') || '';
                        var studentEmail = $row.data('student-email') || '';
                        var match = studentName.indexOf(searchValue) > -1 || studentEmail.indexOf(searchValue) > -1;
                        $row.toggle(match);
                    });
                    $('.course-section').each(function() {
                        var $section = $(this);
                        var visibleRows = $section.find('.student-row:visible').length;
                        var totalRows = $section.find('.student-row').length;
                        if (searchValue !== '' && visibleRows === 0 && totalRows > 0) {
                            $section.hide();
                        } else if (totalRows > 0) {
                            $section.show();
                        }
                    });
                    $('.enroll-student-row').each(function() {
                        var $row = $(this);
                        var studentName = $row.data('student-name') || '';
                        var studentEmail = $row.data('student-email') || '';
                        var match = studentName.indexOf(searchValue) > -1 || studentEmail.indexOf(searchValue) > -1;
                        $row.toggle(match);
                    });
                }
                function filterCourseStudents(courseId) {
                    var $input = $('.course-search-input[data-course-id="' + courseId + '"]');
                    var searchValue = $input.val().toLowerCase().trim();
                    $('.course-' + courseId + '-no-results').remove();
                    var hasVisibleRows = false;
                    $('.course-' + courseId + '-student').each(function() {
                        var $row = $(this);
                        var studentName = $row.data('student-name') || '';
                        var studentEmail = $row.data('student-email') || '';
                        var match = studentName.indexOf(searchValue) > -1 || studentEmail.indexOf(searchValue) > -1;
                        if (match) {
                            $row.show();
                            hasVisibleRows = true;
                        } else {
                            $row.hide();
                        }
                    });
                    var $tbody = $('.course-students-tbody[data-course-id="' + courseId + '"]');
                    if (searchValue !== '' && !hasVisibleRows) {
                        if ($tbody.find('.course-' + courseId + '-no-results').length === 0) {
                            $tbody.append(
                                '<tr class="course-' + courseId + '-no-results">' +
                                '<td colspan="5" class="text-center py-3">' +
                                '<div class="alert alert-info mb-0">' +
                                '<i class="bi bi-info-circle"></i> No students found matching your search.' +
                                '</div>' +
                                '</td>' +
                                '</tr>'
                            );
                        }
                    }
                }
                function filterEnrollStudents() {
                    var searchValue = $('#enrollStudentsSearchInput').val().toLowerCase().trim();
                    $('#enrollStudentsNoResults').remove();
                    var hasVisibleRows = false;
                    $('.enroll-student-row').each(function() {
                        var $row = $(this);
                        var studentName = $row.data('student-name') || '';
                        var studentEmail = $row.data('student-email') || '';
                        var match = studentName.indexOf(searchValue) > -1 || studentEmail.indexOf(searchValue) > -1;
                        if (match) {
                            $row.show();
                            hasVisibleRows = true;
                        } else {
                            $row.hide();
                        }
                    });
                    var $tbody = $('#enrollStudentsTableBody');
                    if (searchValue !== '' && !hasVisibleRows) {
                        if ($tbody.find('#enrollStudentsNoResults').length === 0) {
                            var colspan = $tbody.find('tr:first td').length || 2;
                            $tbody.append(
                                '<tr id="enrollStudentsNoResults">' +
                                '<td colspan="' + colspan + '" class="text-center py-3">' +
                                '<div class="alert alert-info mb-0">' +
                                '<i class="bi bi-info-circle"></i> No students found matching your search.' +
                                '</div>' +
                                '</td>' +
                                '</tr>'
                            );
                        }
                    }
                }
                $(document).on('keyup input paste', '.course-search-input', function() {
                    var courseId = $(this).data('course-id');
                    filterCourseStudents(courseId);
                });
                $(document).on('submit', '.course-search-form', function(e) {
                    e.preventDefault();
                    var courseId = $(this).data('course-id');
                    filterCourseStudents(courseId);
                    return false;
                });
                $(document).on('keyup input paste', '#enrollStudentsSearchInput', filterEnrollStudents);
                $(document).on('submit', '#enrollStudentsSearchForm', function(e) {
                    e.preventDefault();
                    filterEnrollStudents();
                    return false;
                });
            });
        }
    });
}
</script>

<?= $this->endSection() ?>

