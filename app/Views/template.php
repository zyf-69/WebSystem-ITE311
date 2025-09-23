<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ITE311-DIGA</title>

    <!-- ✅ Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- ✅ Bootstrap Blue Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('/') ?>">ITE311-DIGA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
          data-bs-target="#navbarNav" aria-controls="navbarNav" 
          aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" href="<?= base_url('/') ?>">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('about') ?>">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('contact') ?>">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('login') ?>">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('register') ?>">Register</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- ✅ Main Content Section -->
    <div class="container mt-4">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- ✅ Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
