<?php
// Extract variables from $data array
extract($data);
?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Manage Bookings</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                    <button class="btn btn-primary" onclick="exportBookings()">
                        <i class="bi bi-download me-2"></i>Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Status Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm cursor-pointer" onclick="filterByStatus('pending')">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="bi bi-clock text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Pending Bookings</h6>
                            <h3 class="mb-0"><?php echo $pending_count; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm cursor-pointer" onclick="filterByStatus('confirmed')">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="bi bi-check-circle text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Confirmed</h6>
                            <h3 class="mb-0"><?php echo $confirmed_count; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm cursor-pointer" onclick="filterByStatus('completed')">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="bi bi-check-all text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Completed</h6>
                            <h3 class="mb-0"><?php echo $completed_count; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm cursor-pointer" onclick="filterByStatus('canceled')">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="bi bi-x-circle text-danger fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Canceled</h6>
                            <h3 class="mb-0"><?php echo $canceled_count; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Therapist</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                    No bookings found
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>#<?php echo $booking['appointment_id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['therapist_name']); ?></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span><?php echo date('M d, Y', strtotime($booking['appointment_date'])); ?></span>
                                        <small class="text-muted"><?php echo date('h:i A', strtotime($booking['start_time'])); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo getStatusBadgeClass($booking['status']); ?>">
                                        <?php echo getStatusLabel($booking['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo getPaymentStatusBadgeClass($booking['payment_status']); ?>">
                                        <?php echo getPaymentStatusLabel($booking['payment_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <?php foreach (getActionButtons($booking) as $button): ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-<?php echo $button['class']; ?>" 
                                                    onclick="<?php echo $button['action']; ?>">
                                                <i class="bi bi-<?php echo $button['icon']; ?> me-1"></i>
                                                <?php echo $button['label']; ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Bookings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm" action="" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <input type="date" name="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Therapist</label>
                        <select name="therapist" class="form-select">
                            <option value="">All Therapists</option>
                            <?php foreach ($therapists as $therapist): ?>
                                <option value="<?php echo $therapist['user_id']; ?>">
                                    <?php echo htmlspecialchars($therapist['full_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
            </div>
        </div>
    </div>
</div>

<style>
.cursor-pointer {
    cursor: pointer;
}
.cursor-pointer:hover {
    transform: translateY(-2px);
    transition: transform 0.2s;
}
</style>

<script src="/cit17-final-project/public/assets/js/admin/services.js"></script>