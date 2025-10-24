<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ITE311-DIGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background:#f8f9fa; }
        .navbar { box-shadow: 0 1px 2px rgba(0,0,0,.05); }
        .container-main { max-width: 980px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-light bg-white">
      <div class="container container-main">
        <a class="navbar-brand" href="<?= base_url('/') ?>">
            <i class="bi bi-mortarboard-fill"></i> ITE311-DIGA
        </a>
        <ul class="navbar-nav ms-auto">
          <?php if (session()->get('isLoggedIn')): ?>
            <!-- Logged-in user navigation -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('announcements') ?>">
                    <i class="bi bi-megaphone"></i> Announcements
                </a>
            </li>
            
            <?php if (session()->get('role') === 'admin'): ?>
              <!-- Admin-specific navigation -->
              <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('admin/users') ?>">
                      <i class="bi bi-people"></i> Manage Users
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="<?= base_url('admin/create-user') ?>">
                      <i class="bi bi-person-plus"></i> Create User
                  </a>
              </li>
            <?php elseif (session()->get('role') === 'teacher'): ?>
              <!-- Teacher-specific navigation -->
              <li class="nav-item">
                  <a class="nav-link" href="#">
                      <i class="bi bi-book"></i> My Courses
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="#">
                      <i class="bi bi-people"></i> My Students
                  </a>
              </li>
            <?php else: ?>
              <!-- Student-specific navigation -->
              <li class="nav-item">
                  <a class="nav-link" href="#">
                      <i class="bi bi-book"></i> My Courses
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="#">
                      <i class="bi bi-file-text"></i> Assignments
                  </a>
              </li>
            <?php endif; ?>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i> <?= esc(session()->get('user_name')) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#">
                        <i class="bi bi-person"></i> Profile
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="bi bi-gear"></i> Settings
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a></li>
                </ul>
            </li>
          <?php else: ?>
            <!-- Public navigation (not logged in) -->
            <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('about') ?>">About</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('contact') ?>">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('login') ?>">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('register') ?>">
                <i class="bi bi-person-plus"></i> Register
            </a></li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>

    <main class="container container-main py-4">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
