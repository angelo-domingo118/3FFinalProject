<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">My Appointments</h4>
                <a href="<?php echo BASE_URL; ?>/public/booking" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Book New Appointment
                </a>
            </div>
        </div>
    </div>

    <!-- Appointment Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form class="row g-3" method="GET">
                        <div class="col-md-4">
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending" <?php echo isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo isset($_GET['status']) && $_GET['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="completed" <?php echo isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="canceled" <?php echo isset($_GET['status']) && $_GET['status'] === 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="row">
        <div class="col-12">
            <?php if (empty($appointments)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted mb-3"></i>
                    <h5>No appointments found</h5>
                    <p class="text-muted">Book your first appointment to get started</p>
                    <a href="<?php echo BASE_URL; ?>/public/booking" class="btn btn-primary">
                        Book Now
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($appointments as $appointment): ?>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($appointment['service_name']); ?></h5>
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-clock me-2"></i>
                                        <?php echo date('l, F j, Y', strtotime($appointment['appointment_date'])); ?> at 
                                        <?php echo date('g:i A', strtotime($appointment['start_time'])); ?>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <span class="badge bg-<?php echo getStatusBadgeClass($appointment['status']); ?>">
                                        <?php echo ucfirst($appointment['status']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                    <?php if ($appointment['status'] === 'pending' || $appointment['status'] === 'confirmed'): ?>
                                        <button class="btn btn-outline-danger btn-sm me-2" 
                                                onclick="cancelAppointment(<?php echo $appointment['appointment_id']; ?>)">
                                            Cancel
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($appointment['status'] === 'completed' && !$appointment['has_review']): ?>
                                        <button class="btn btn-outline-primary btn-sm"
                                                onclick="leaveReview(<?php echo $appointment['appointment_id']; ?>)">
                                            Leave Review
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <input type="hidden" name="appointment_id" id="reviewAppointmentId">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>">
                                <label for="star<?php echo $i; ?>"><i class="bi bi-star-fill"></i></label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comment</label>
                        <textarea class="form-control" name="comment" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitReview()">Submit Review</button>
            </div>
        </div>
    </div>
</div> 