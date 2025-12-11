<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-book"></i> My Courses</h1>
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

            <!-- Pending Enrollment Requests -->
            <?php if (!empty($pendingEnrollments)): ?>
                <div class="card mb-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-hourglass-split"></i> Pending Enrollment Requests
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($pendingEnrollments as $enrollment): ?>
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
            <?php endif; ?>

            <!-- Search Form -->
            <div class="row mb-4">
                <div class="col-md-6">
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

            <!-- Enrolled Courses -->
            <?php if (empty($enrolledCourses)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> You are not enrolled in any courses yet. Browse available courses on the dashboard to enroll.
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-book-check"></i> My Enrolled Courses
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($enrolledCourses as $enrollment): ?>
                                <div class="list-group-item course-card">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <i class="bi bi-book-fill text-success"></i>
                                                <?= esc($enrollment['title'] ?? 'Course #' . $enrollment['course_id']) ?>
                                                <span class="badge bg-success ms-2">Enrolled</span>
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
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Search Script -->
<script>
// Ensure this runs after jQuery and DOM are ready
if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function($) {
        // Auto-filtering as user types (Instant Search)
        function filterStudentCourses() {
            var searchValue = $('#searchInput').val().toLowerCase().trim();
            
            // Remove previous no results message
            $('#noResultsMessage').remove();
            
            // Filter course cards
            var hasVisibleCards = false;
            $('.course-card').each(function() {
                var $card = $(this);
                var cardText = $card.text().toLowerCase();
                
                if (cardText.indexOf(searchValue) > -1) {
                    $card.show();
                    hasVisibleCards = true;
                } else {
                    $card.hide();
                }
            });
            
            // Show message if no results and search has value
            if (searchValue !== '' && !hasVisibleCards) {
                $('.list-group').append(
                    '<div class="list-group-item" id="noResultsMessage">' +
                    '<div class="alert alert-info mb-0">' +
                    '<i class="bi bi-info-circle"></i> No courses found matching your search.' +
                    '</div>' +
                    '</div>'
                );
            }
        }
        
        // Bind events for auto-filtering using event delegation
        $(document).on('keyup input paste', '#searchInput', filterStudentCourses);
        
        // Prevent form submission - just use auto-filtering
        $(document).on('submit', '#searchForm', function(e) {
            e.preventDefault();
            filterStudentCourses();
            return false;
        });
    });
} else {
    // Fallback if jQuery loads later
    window.addEventListener('load', function() {
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                function filterStudentCourses() {
                    var searchValue = $('#searchInput').val().toLowerCase().trim();
                    $('#noResultsMessage').remove();
                    var hasVisibleCards = false;
                    $('.course-card').each(function() {
                        var $card = $(this);
                        var cardText = $card.text().toLowerCase();
                        if (cardText.indexOf(searchValue) > -1) {
                            $card.show();
                            hasVisibleCards = true;
                        } else {
                            $card.hide();
                        }
                    });
                    if (searchValue !== '' && !hasVisibleCards) {
                        $('.list-group').append(
                            '<div class="list-group-item" id="noResultsMessage">' +
                            '<div class="alert alert-info mb-0">' +
                            '<i class="bi bi-info-circle"></i> No courses found matching your search.' +
                            '</div>' +
                            '</div>'
                        );
                    }
                }
                $(document).on('keyup input paste', '#searchInput', filterStudentCourses);
                $(document).on('submit', '#searchForm', function(e) {
                    e.preventDefault();
                    filterStudentCourses();
                    return false;
                });
            });
        }
    });
}
</script>
<?= $this->endSection() ?>

