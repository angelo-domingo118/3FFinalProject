<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2">
        <div>
            <h1 class="h3 mb-0">Dashboard Overview</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted">Last updated: <?php echo date('M d, Y h:i A'); ?></span>
            <button class="btn btn-sm btn-light" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-4 mb-4">
        <!-- Today's Bookings -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="card-icon bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-calendar-check text-primary fs-4"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/admin/bookings">View All Bookings</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/admin/reports">View Booking Report</a></li>
                            </ul>
                        </div>
                    </div>
                    <h6 class="text-muted mb-2">Today's Bookings</h6>
                    <div class="d-flex align-items-baseline">
                        <h3 class="mb-0"><?php echo $todays_bookings ?? 0; ?></h3>
                        <?php if (isset($booking_trend) && $booking_trend > 0): ?>
                        <span class="badge bg-success bg-opacity-10 text-success ms-2">
                            <i class="bi bi-arrow-up"></i> <?php echo $booking_trend; ?>%
                        </span>
                        <?php endif; ?>
                    </div>
                    <small class="text-muted">vs. last week</small>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="card-icon bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-currency-dollar text-success fs-4"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/admin/payments">View All Payments</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/admin/reports">View Revenue Report</a></li>
                            </ul>
                        </div>
                    </div>
                    <h6 class="text-muted mb-2">Today's Revenue</h6>
                    <div class="d-flex align-items-baseline">
                        <h3 class="mb-0">₱<?php echo number_format($todays_revenue ?? 0, 2); ?></h3>
                        <?php if (isset($revenue_trend) && $revenue_trend > 0): ?>
                        <span class="badge bg-success bg-opacity-10 text-success ms-2">
                            <i class="bi bi-arrow-up"></i> <?php echo $revenue_trend; ?>%
                        </span>
                        <?php endif; ?>
                    </div>
                    <small class="text-muted">vs. last week</small>
                </div>
            </div>
        </div>

        <!-- Active Services -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="card-icon bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-grid text-info fs-4"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/admin/services">Manage Services</a></li>
                            </ul>
                        </div>
                    </div>
                    <h6 class="text-muted mb-2">Active Services</h6>
                    <div class="d-flex align-items-baseline">
                        <h3 class="mb-0"><?php echo $active_services ?? 0; ?></h3>
                    </div>
                    <small class="text-muted">Services available</small>
                </div>
            </div>
        </div>

        <!-- Available Therapists -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="card-icon bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-people text-warning fs-4"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/admin/therapists">Manage Therapists</a></li>
                            </ul>
                        </div>
                    </div>
                    <h6 class="text-muted mb-2">Available Therapists</h6>
                    <div class="d-flex align-items-baseline">
                        <h3 class="mb-0"><?php echo $available_therapists ?? 0; ?></h3>
                        <span class="text-muted ms-2">/ <?php echo $total_therapists ?? 0; ?></span>
                    </div>
                    <small class="text-muted">Currently available</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="row g-4">
        <!-- Recent Bookings -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Bookings</h5>
                    <a href="<?php echo BASE_URL; ?>/public/admin/bookings" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Customer</th>
                                    <th class="border-0">Service</th>
                                    <th class="border-0">Date & Time</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_bookings)): ?>
                                    <?php foreach ($recent_bookings as $booking): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-initial rounded bg-secondary bg-opacity-10 text-secondary p-2 me-2">
                                                    <?php echo strtoupper(substr($booking['customer_name'], 0, 1)); ?>
                                                </div>
                                                <?php echo $booking['customer_name']; ?>
                                            </div>
                                        </td>
                                        <td><?php echo $booking['service_name']; ?></td>
                                        <td><?php echo date('M d, Y h:i A', strtotime($booking['booking_date'])); ?></td>
                                        <td>
                                            <?php
                                            $status_class = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ][$booking['status']];
                                            ?>
                                            <span class="badge bg-<?php echo $status_class; ?> bg-opacity-10 text-<?php echo $status_class; ?>">
                                                <?php echo ucfirst($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/public/admin/bookings/view/<?php echo $booking['id']; ?>" 
                                               class="btn btn-sm btn-light">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-calendar-x fs-4 d-block mb-2"></i>
                                            No recent bookings found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>/public/admin/bookings/create" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>New Booking
                        </a>
                        <a href="<?php echo BASE_URL; ?>/public/admin/services/create" class="btn btn-outline-primary">
                            <i class="bi bi-grid-plus me-2"></i>Add Service
                        </a>
                        <a href="<?php echo BASE_URL; ?>/public/admin/therapists/create" class="btn btn-outline-primary">
                            <i class="bi bi-person-plus me-2"></i>Add Therapist
                        </a>
                    </div>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Today's Schedule</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (!empty($todays_schedule)): ?>
                            <?php foreach ($todays_schedule as $schedule): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo $schedule['service_name']; ?></h6>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            <?php echo date('h:i A', strtotime($schedule['start_time'])); ?> - 
                                            <?php echo date('h:i A', strtotime($schedule['end_time'])); ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">
                                        <?php echo $schedule['therapist_name']; ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-calendar2-x fs-4 d-block mb-2"></i>
                                No appointments scheduled
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>