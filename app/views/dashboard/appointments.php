<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">My Appointments</h4>
                    <p class="text-muted mb-0">Manage all your wellness appointments</p>
                </div>
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Filter Appointments</h6>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="viewMode" id="cardView" checked>
                            <label class="btn btn-outline-primary" for="cardView">
                                <i class="bi bi-grid"></i>
                            </label>
                            <input type="radio" class="btn-check" name="viewMode" id="listView">
                            <label class="btn btn-outline-primary" for="listView">
                                <i class="bi bi-list"></i>
                            </label>
                        </div>
                    </div>
                    <form class="row g-3" method="GET" action="<?php echo BASE_URL; ?>/public/dashboard/appointments">
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending" <?php echo isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo isset($_GET['status']) && $_GET['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="completed" <?php echo isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="canceled" <?php echo isset($_GET['status']) && $_GET['status'] === 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" name="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-funnel me-2"></i>Apply Filters
                                </button>
                                <?php if (!empty($_GET['status']) || !empty($_GET['date'])): ?>
                                    <a href="<?php echo BASE_URL; ?>/public/dashboard/appointments" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
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
                    <p class="text-muted">
                        <?php if (!empty($_GET['status']) || !empty($_GET['date'])): ?>
                            Try adjusting your filters or
                        <?php endif; ?>
                        Book your first appointment to get started
                    </p>
                    <a href="<?php echo BASE_URL; ?>/public/booking" class="btn btn-primary">
                        Book Now
                    </a>
                </div>
            <?php else: ?>
                <div id="appointmentsCardView">
                    <?php foreach ($appointments as $appointment): ?>
                        <div class="card border-0 shadow-sm mb-3 appointment-card">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-lg-1 col-md-2 text-center mb-3 mb-md-0">
                                        <div class="appointment-date rounded-circle bg-light p-3 mx-auto" style="width: 80px; height: 80px;">
                                            <div class="month text-uppercase small"><?php echo date('M', strtotime($appointment['appointment_date'])); ?></div>
                                            <div class="day h4 mb-0"><?php echo date('d', strtotime($appointment['appointment_date'])); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 mb-3 mb-md-0">
                                        <h5 class="card-title mb-1"><?php echo htmlspecialchars($appointment['service_name']); ?></h5>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-clock me-2"></i>
                                            <?php echo date('g:i A', strtotime($appointment['start_time'])); ?>
                                            (<?php echo $appointment['duration']; ?> mins)
                                        </p>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-person me-2"></i>
                                            <?php echo htmlspecialchars($appointment['therapist_name'] ?? 'Not Assigned'); ?>
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-md-3 mb-3 mb-md-0">
                                        <span class="badge bg-<?php 
                                            echo match($appointment['status']) {
                                                'pending' => 'warning',
                                                'confirmed' => 'success',
                                                'completed' => 'info',
                                                'canceled' => 'danger',
                                                default => 'secondary'
                                            };
                                        ?> p-2">
                                            <i class="bi bi-<?php 
                                                echo match($appointment['status']) {
                                                    'pending' => 'hourglass-split',
                                                    'confirmed' => 'check-circle',
                                                    'completed' => 'trophy',
                                                    'canceled' => 'x-circle',
                                                    default => 'circle'
                                                };
                                            ?> me-2"></i>
                                            <?php echo ucfirst($appointment['status']); ?>
                                        </span>
                                    </div>
                                    <div class="col-lg-4 col-md-3 text-md-end">
                                        <div class="d-flex gap-2 justify-content-md-end">
                                            <?php if ($appointment['status'] === 'pending' || $appointment['status'] === 'confirmed'): ?>
                                                <button class="btn btn-outline-primary" 
                                                        onclick="rescheduleAppointment(<?php echo $appointment['appointment_id']; ?>)">
                                                    <i class="bi bi-calendar2-plus me-2"></i>Reschedule
                                                </button>
                                                <button class="btn btn-outline-danger" 
                                                        onclick="cancelAppointment(<?php echo $appointment['appointment_id']; ?>)">
                                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($appointment['status'] === 'completed' && !$appointment['has_review']): ?>
                                                <button class="btn btn-outline-primary"
                                                        onclick="leaveReview(<?php echo $appointment['appointment_id']; ?>)">
                                                    <i class="bi bi-star me-2"></i>Review
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="appointmentsListView" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Service</th>
                                    <th>Therapist</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></div>
                                            <div class="small text-muted"><?php echo date('g:i A', strtotime($appointment['start_time'])); ?></div>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['therapist_name'] ?? 'Not Assigned'); ?></td>
                                        <td><?php echo $appointment['duration']; ?> mins</td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo match($appointment['status']) {
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'completed' => 'info',
                                                    'canceled' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?> p-2">
                                                <?php echo ucfirst($appointment['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <?php if ($appointment['status'] === 'pending' || $appointment['status'] === 'confirmed'): ?>
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            onclick="rescheduleAppointment(<?php echo $appointment['appointment_id']; ?>)"
                                                            title="Reschedule">
                                                        <i class="bi bi-calendar2-plus"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            onclick="cancelAppointment(<?php echo $appointment['appointment_id']; ?>)"
                                                            title="Cancel">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($appointment['status'] === 'completed' && !$appointment['has_review']): ?>
                                                    <button class="btn btn-sm btn-outline-primary"
                                                            onclick="leaveReview(<?php echo $appointment['appointment_id']; ?>)"
                                                            title="Leave Review">
                                                        <i class="bi bi-star"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    <div class="mb-4">
                        <label class="form-label">Rating</label>
                        <div class="rating d-flex justify-content-center gap-2">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" class="btn-check" name="rating" value="<?php echo $i; ?>" 
                                       id="star<?php echo $i; ?>" autocomplete="off" required>
                                <label class="btn btn-outline-warning" for="star<?php echo $i; ?>">
                                    <?php echo $i; ?> <i class="bi bi-star-fill"></i>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Experience</label>
                        <textarea class="form-control" name="comment" rows="4" 
                                  placeholder="Tell us about your experience..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitReview()">
                    <i class="bi bi-send me-2"></i>Submit Review
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reschedule Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rescheduleForm">
                    <input type="hidden" name="appointment_id" id="rescheduleAppointmentId">
                    <div class="mb-3">
                        <label class="form-label">New Date</label>
                        <input type="date" class="form-control" name="new_date" required 
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Time</label>
                        <select class="form-select" name="new_time" required>
                            <?php
                            $start = strtotime('9:00');
                            $end = strtotime('17:00');
                            for ($time = $start; $time <= $end; $time += 1800) {
                                echo '<option value="' . date('H:i:s', $time) . '">' . date('g:i A', $time) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Rescheduling</label>
                        <textarea class="form-control" name="reason" rows="3" 
                                 placeholder="Please provide a reason for rescheduling..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitReschedule()">
                    <i class="bi bi-calendar2-check me-2"></i>Confirm Reschedule
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.appointment-card {
    transition: transform 0.2s;
}
.appointment-card:hover {
    transform: translateY(-2px);
}
.appointment-date {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.rating .btn-outline-warning {
    min-width: 60px;
}
.rating .btn-outline-warning:hover {
    background-color: #ffc107;
    color: #000;
}
.rating .btn-check:checked + .btn-outline-warning {
    background-color: #ffc107;
    color: #000;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const cardView = document.getElementById('cardView');
    const listView = document.getElementById('listView');
    const appointmentsCardView = document.getElementById('appointmentsCardView');
    const appointmentsListView = document.getElementById('appointmentsListView');

    cardView.addEventListener('change', function() {
        appointmentsCardView.style.display = 'block';
        appointmentsListView.style.display = 'none';
        localStorage.setItem('appointmentsViewMode', 'card');
    });

    listView.addEventListener('change', function() {
        appointmentsCardView.style.display = 'none';
        appointmentsListView.style.display = 'block';
        localStorage.setItem('appointmentsViewMode', 'list');
    });

    // Load saved view preference
    const savedViewMode = localStorage.getItem('appointmentsViewMode');
    if (savedViewMode === 'list') {
        listView.checked = true;
        appointmentsCardView.style.display = 'none';
        appointmentsListView.style.display = 'block';
    }
});

// Reschedule functionality
function rescheduleAppointment(appointmentId) {
    document.getElementById('rescheduleAppointmentId').value = appointmentId;
    new bootstrap.Modal(document.getElementById('rescheduleModal')).show();
}

function submitReschedule() {
    const form = document.getElementById('rescheduleForm');
    const formData = new FormData(form);
    
    fetch(`${BASE_URL}/public/api/appointments/reschedule`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error rescheduling appointment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error rescheduling appointment');
    });
}
</script>