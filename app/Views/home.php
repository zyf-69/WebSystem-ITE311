<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="jumbotron bg-light p-5 rounded">
            <h1 class="display-4">Welcome to ITE311-DIGA</h1>
            <p class="lead">Learning Management System for Digital Arts</p>
            <hr class="my-4">
            <p>This is a comprehensive LMS built with CodeIgniter 4, featuring user authentication, course management, and more.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a class="btn btn-primary btn-lg" href="/auth/register" role="button">Get Started</a>
                <a class="btn btn-outline-secondary btn-lg" href="/auth/login" role="button">Login</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="card-title">📚 Courses</h5>
                <p class="card-text">Access a wide range of digital arts courses designed for all skill levels.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="card-title">👨‍🏫 Instructors</h5>
                <p class="card-text">Learn from experienced professionals in the digital arts field.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="card-title">📊 Progress</h5>
                <p class="card-text">Track your learning progress and achievements in real-time.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
