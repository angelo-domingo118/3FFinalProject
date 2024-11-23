<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="card-title mb-3">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h4>
                    <p class="card-text text-muted">Manage your appointments and wellness journey from your personal dashboard.</p>
                    <a href="<?php echo BASE_URL; ?>/public/booking" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-2"></i>Book New Appointment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h6 class="card-subtitle text-muted mb-0">Upcoming Appointments</h6>
                    </div>
                    <h3 class="card-title mb-0"><?php echo $upcoming_count; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon bg-success bg-opacity-10 text-success rounded p-3 me-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h6 class="card-subtitle text-muted mb-0">Completed Sessions</h6>
                    </div>
                    <h3 class="card-title mb-0"><?php echo $completed_count; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon bg-warning bg-opacity-10 text-warning rounded p-3 me-3">
                            <i class="bi bi-star"></i>
                        </div>
                        <h6 class="card-subtitle text-muted mb-0">Reviews Given</h6>
                    </div>
                    <h3 class="card-title mb-0"><?php echo $reviews_count; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Appointment -->
    <?php if (!empty($next_appointment)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Your Next Appointment</h5>
                    <div class="d-flex align-items-center">
                        <div class="appointment-icon bg-primary bg-opacity-10 p-3 rounded-circle me-4">
                            <i class="bi bi-calendar-event text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1"><?php echo htmlspecialchars($next_appointment['service_name']); ?></h6>
                            <p class="text-muted mb-0">
                                <i class="bi bi-clock me-2"></i>
                                <?php echo date('l, F j, Y', strtotime($next_appointment['appointment_date'])); ?> at 
                                <?php echo date('g:i A', strtotime($next_appointment['start_time'])); ?>
                            </p>
                        </div>
                        <div class="ms-auto">
                            <a href="<?php echo BASE_URL; ?>/public/dashboard/appointments/<?php echo $next_appointment['appointment_id']; ?>" 
                               class="btn btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Your Next Appointment</h5>
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                        <p class="text-muted">No upcoming appointments</p>
                        <a href="<?php echo BASE_URL; ?>/public/booking" class="btn btn-primary">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Promotions & Rewards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Current Promotions & Rewards</h5>
                        <?php if (!empty($promotions)): ?>
                            <span class="badge bg-primary"><?php echo count($promotions); ?> Active</span>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($promotions)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-gift display-4 text-muted mb-3"></i>
                            <p class="text-muted">No active promotions at the moment</p>
                            <small class="text-muted">Check back soon for special offers!</small>
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($promotions as $promo): ?>
                                <div class="col-md-6">
                                    <div class="promo-card bg-light rounded p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="promo-icon bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                                                <i class="bi bi-percent"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($promo['discount_percent']); ?>% OFF</h6>
                                                <p class="text-muted small mb-0">
                                                    Valid until <?php echo date('F j, Y', strtotime($promo['end_date'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <p class="mb-3"><?php echo htmlspecialchars($promo['description']); ?></p>
                                        <div class="promo-code-box bg-white rounded p-2 text-center">
                                            <small class="text-muted d-block mb-1">Promo Code</small>
                                            <span class="fw-bold text-primary"><?php echo htmlspecialchars($promo['promo_code']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 