<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ITE311-DIGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; }
        .navbar { box-shadow: 0 1px 2px rgba(0,0,0,.05); }
        .container-main { max-width: 980px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-light bg-white">
      <div class="container container-main">
        <a class="navbar-brand" href="<?= base_url('/') ?>">ITE311-DIGA</a>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('about') ?>">About</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('contact') ?>">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('login') ?>">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('register') ?>">Register</a></li>
        </ul>
      </div>
    </nav>

    <main class="container container-main py-4">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
