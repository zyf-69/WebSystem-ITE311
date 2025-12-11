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

