<!-- Dynamic Navigation Bar -->
<!-- This header is included in the main template and shows role-specific navigation items -->
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
                
                <!-- Notifications dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        <span id="notificationBadge" class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="display: none; font-size: 0.7rem;">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" id="notificationList" aria-labelledby="notificationDropdown" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                        <li><h6 class="dropdown-header d-flex justify-content-between align-items-center">
                            <span>Notifications</span>
                            <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none" id="refreshNotifications" title="Refresh">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </h6></li>
                        <li><div id="notificationItems" class="px-3 py-2 text-muted small">Loading notifications...</div></li>
                    </ul>
                </li>
                
                <?php 
                // Dynamic role-based navigation items
                $userRole = session()->get('role');
                ?>
                
                <?php if ($userRole === 'admin'): ?>
                    <!-- Admin-specific navigation items -->
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
                <?php elseif ($userRole === 'teacher'): ?>
                    <!-- Teacher-specific navigation items -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('my-course') ?>">
                            <i class="bi bi-book"></i> My Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('my-students') ?>">
                            <i class="bi bi-people"></i> My Students
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Student-specific navigation items -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('my-course') ?>">
                            <i class="bi bi-book"></i> My Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-file-text"></i> Assignments
                        </a>
                    </li>
                <?php endif; ?>
                
                <!-- User dropdown menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> <?= esc(session()->get('user_name')) ?>
                        <?php if ($userRole === 'admin'): ?>
                            <span class="badge bg-danger ms-1">Admin</span>
                        <?php elseif ($userRole === 'teacher'): ?>
                            <span class="badge bg-success ms-1">Teacher</span>
                        <?php else: ?>
                            <span class="badge bg-primary ms-1">Student</span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="<?= base_url('dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a></li>
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
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/') ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('about') ?>">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('contact') ?>">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('login') ?>">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('register') ?>">
                        <i class="bi bi-person-plus"></i> Register
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

