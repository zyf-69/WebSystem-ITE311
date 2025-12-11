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

            <?php if (empty($courses)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No courses assigned to you yet. Please contact an administrator.
                </div>
            <?php else: ?>
                <div class="row" id="coursesContainer">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-6 mb-4 course-card">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-book-fill"></i> <?= esc($course['title']) ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?= esc($course['description'] ?? 'No description available') ?></p>
                                    
                                    <div class="mb-2">
                                        <?php if (!empty($course['level'])): ?>
                                            <span class="badge bg-info"><?= esc($course['level']) ?></span>
                                        <?php endif; ?>
                                        <?php 
                                        $status = $course['status'] ?? 'active';
                                        $statusClass = $status === 'active' ? 'success' : ($status === 'inactive' ? 'secondary' : 'warning');
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>"><?= ucfirst(esc($status)) ?></span>
                                    </div>
                                    
                                    <?php if (!empty($course['start_time']) && !empty($course['end_time'])): ?>
                                        <p class="mb-1">
                                            <i class="bi bi-clock"></i> 
                                            <?= date('h:i A', strtotime($course['start_time'])) ?> - 
                                            <?= date('h:i A', strtotime($course['end_time'])) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($course['schedule_days'])): ?>
                                        <p class="mb-1">
                                            <i class="bi bi-calendar"></i> 
                                            <?= esc($course['schedule_days']) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($course['room'])): ?>
                                        <p class="mb-1">
                                            <i class="bi bi-geo-alt"></i> 
                                            <?= esc($course['room']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-light">
                                    <div class="mb-2">
                                        <strong>Course Materials:</strong>
                                        <?php if (empty($course['materials'])): ?>
                                            <div class="text-muted small">No materials uploaded yet.</div>
                                        <?php else: ?>
                                            <ul class="list-unstyled mb-0 small">
                                                <?php foreach ($course['materials'] as $material): ?>
                                                    <li class="d-flex align-items-center mb-1">
                                                        <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                                        <span class="flex-grow-1"><?= esc($material['file_name']) ?></span>
                                                        <a class="btn btn-sm btn-outline-primary" href="<?= base_url('materials/download/' . $material['id']) ?>">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">
                                                <i class="bi bi-people"></i> 
                                                <?= $course['total_students'] ?? 0 ?> student(s)
                                                <?php if (($course['pending_count'] ?? 0) > 0): ?>
                                                    <span class="badge bg-warning text-dark ms-1">
                                                        <?= $course['pending_count'] ?> pending
                                                    </span>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <div class="btn-group">
                                            <a href="<?= base_url('course/' . $course['id'] . '/upload') ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-upload"></i> Upload Materials
                                            </a>
                                            <a href="<?= base_url('my-students') ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-people"></i> View Students
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
        function filterTeacherCourses() {
            var searchValue = $('#searchInput').val().toLowerCase().trim();
            
            // Remove previous no results message
            $('#noResultsMessage').remove();
            
            // Filter course cards
            var hasVisibleCards = false;
            $('.course-card').each(function() {
                var $card = $(this);
                var $container = $card.closest('.col-md-6');
                var cardText = $card.text().toLowerCase();
                
                if (cardText.indexOf(searchValue) > -1) {
                    $container.show();
                    hasVisibleCards = true;
                } else {
                    $container.hide();
                }
            });
            
            // Show message if no results and search has value
            if (searchValue !== '' && !hasVisibleCards) {
                $('#coursesContainer').append(
                    '<div class="col-12" id="noResultsMessage">' +
                    '<div class="alert alert-info">' +
                    '<i class="bi bi-info-circle"></i> No courses found matching your search.' +
                    '</div>' +
                    '</div>'
                );
            }
        }
        
        // Bind events for auto-filtering using event delegation
        $(document).on('keyup input paste', '#searchInput', filterTeacherCourses);
        
        // Prevent form submission - just use auto-filtering
        $(document).on('submit', '#searchForm', function(e) {
            e.preventDefault();
            filterTeacherCourses();
            return false;
        });
    });
} else {
    // Fallback if jQuery loads later
    window.addEventListener('load', function() {
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                function filterTeacherCourses() {
                    var searchValue = $('#searchInput').val().toLowerCase().trim();
                    $('#noResultsMessage').remove();
                    var hasVisibleCards = false;
                    $('.course-card').each(function() {
                        var $card = $(this);
                        var $container = $card.closest('.col-md-6');
                        var cardText = $card.text().toLowerCase();
                        if (cardText.indexOf(searchValue) > -1) {
                            $container.show();
                            hasVisibleCards = true;
                        } else {
                            $container.hide();
                        }
                    });
                    if (searchValue !== '' && !hasVisibleCards) {
                        $('#coursesContainer').append(
                            '<div class="col-12" id="noResultsMessage">' +
                            '<div class="alert alert-info">' +
                            '<i class="bi bi-info-circle"></i> No courses found matching your search.' +
                            '</div>' +
                            '</div>'
                        );
                    }
                }
                $(document).on('keyup input paste', '#searchInput', filterTeacherCourses);
                $(document).on('submit', '#searchForm', function(e) {
                    e.preventDefault();
                    filterTeacherCourses();
                    return false;
                });
            });
        }
    });
}
</script>
<?= $this->endSection() ?>

