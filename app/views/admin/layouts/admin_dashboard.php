<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Wellness Center</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>/public/assets/css/admin.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="shadow-sm">
            <div class="sidebar-header p-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-heart-pulse-fill text-primary fs-4 me-2"></i>
                    <h3 class="mb-0">Admin Panel</h3>
                </div>
            </div>

            <ul class="list-unstyled components p-3">
                <li class="mb-2 <?php echo $active_page === 'dashboard' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/admin" class="d-flex align-items-center p-3 rounded">
                        <i class="bi bi-speedometer2 me-3"></i> Dashboard
                    </a>
                </li>
                <li class="<?php echo $active_page === 'bookings' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/admin/bookings" class="d-flex align-items-center p-3 rounded">
                        <i class="bi bi-calendar-check me-3"></i> Bookings
                    </a>
                </li>
                <li class="<?php echo $active_page === 'services' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/admin/services" class="d-flex align-items-center p-3 rounded">
                        <i class="bi bi-grid me-3"></i> Services
                    </a>
                </li>
                <li class="<?php echo $active_page === 'therapists' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/admin/therapists" class="d-flex align-items-center p-3 rounded">
                        <i class="bi bi-people me-3"></i> Therapists
                    </a>
                </li>
                <li class="<?php echo $active_page === 'payments' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/admin/payments" class="d-flex align-items-center p-3 rounded">
                        <i class="bi bi-credit-card me-3"></i> Payments
                    </a>
                </li>
                <li class="<?php echo $active_page === 'reports' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/public/admin/reports" class="d-flex align-items-center p-3 rounded">
                        <i class="bi bi-graph-up me-3"></i> Reports
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content Area -->
        <div id="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-light d-lg-none">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <span class="me-3"><?php echo $_SESSION['full_name']; ?></span>
                        <a href="<?php echo BASE_URL; ?>/public/logout" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid p-4">
                <?php include $content; ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Define BASE_URL globally for JavaScript
        const BASE_URL = "<?php echo BASE_URL; ?>";
    </script>
    <script src="<?php echo BASE_URL; ?>/public/assets/js/admin.js"></script>
    <?php if ($active_page === 'bookings'): ?>
    <script src="<?php echo BASE_URL; ?>/public/assets/js/admin/bookings.js"></script>
    <?php endif; ?>
</body>
</html>