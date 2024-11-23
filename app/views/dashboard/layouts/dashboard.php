<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SereneBook™</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom Dashboard CSS -->
    <link href="<?php echo BASE_URL; ?>/public/assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo BASE_URL; ?>/public" class="d-flex align-items-center text-decoration-none">
                    <i class="bi bi-heart-pulse-fill text-primary me-2"></i>
                    <span class="fs-5">SereneBook™</span>
                </a>
            </div>

            <ul class="list-unstyled components">
                <li class="<?php echo $active_page === 'overview' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/dashboard">
                        <i class="bi bi-speedometer2 me-2"></i> Overview
                    </a>
                </li>
                <li class="<?php echo $active_page === 'appointments' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/dashboard/appointments">
                        <i class="bi bi-calendar-check me-2"></i> Appointments
                    </a>
                </li>
                <li class="<?php echo $active_page === 'profile' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/dashboard/profile">
                        <i class="bi bi-person me-2"></i> Profile
                    </a>
                </li>
                <li class="<?php echo $active_page === 'reviews' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/dashboard/reviews">
                        <i class="bi bi-star me-2"></i> Reviews
                    </a>
                </li>
                <li class="<?php echo $active_page === 'promotions' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/dashboard/promotions">
                        <i class="bi bi-gift me-2"></i> Promotions
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-link">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/dashboard/profile">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/logout">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="p-4">
                <?php include $content; ?>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/assets/js/dashboard.js"></script>
</body>
</html> 