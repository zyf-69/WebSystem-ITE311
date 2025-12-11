<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-search"></i> Search Results</h1>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <?php if (!empty($searchTerm)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Search results for: <strong><?= esc($searchTerm) ?></strong>
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
                                   name="search_term"
                                   value="<?= esc($searchTerm ?? '') ?>">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Courses Container -->
            <div id="coursesContainer" class="row">
                <?php if (empty($courses)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No courses found matching your search.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card course-card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($course['title'] ?? 'Course #' . $course['id']) ?></h5>
                                    <p class="card-text"><?= esc($course['description'] ?? 'No description available') ?></p>
                                    <a href="<?= base_url('courses/view/' . $course['id']) ?>" class="btn btn-primary">View Course</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Search Script -->
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
            $('#coursesContainer').empty();
            
            if (data.length > 0) {
                $.each(data, function(index, course) {
                    var courseHtml = '<div class="col-md-4 mb-4">' +
                        '<div class="card course-card">' +
                        '<div class="card-body">' +
                        '<h5 class="card-title">' + (course.title || 'Course #' + course.id) + '</h5>' +
                        '<p class="card-text">' + (course.description || 'No description available') + '</p>' +
                        '<a href="<?= base_url('courses/view/') ?>' + course.id + '" class="btn btn-primary">View Course</a>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                    $('#coursesContainer').append(courseHtml);
                });
            } else {
                $('#coursesContainer').html('<div class="col-12"><div class="alert alert-info">No courses found matching your search.</div></div>');
            }
        }).fail(function() {
            $('#coursesContainer').html('<div class="col-12"><div class="alert alert-danger">Error performing search. Please try again.</div></div>');
        });
    });
});
</script>
<?= $this->endSection() ?>

