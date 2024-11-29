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
<?php foreach ($appointments as $appointment): ?>
    <div class="modal fade" id="rescheduleModal<?php echo $appointment['appointment_id']; ?>" tabindex="-1" aria-labelledby="rescheduleModalLabel<?php echo $appointment['appointment_id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="rescheduleModalLabel<?php echo $appointment['appointment_id']; ?>">
                        <i class="bi bi-calendar2-check me-2 text-primary"></i>Reschedule Appointment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <!-- Current Appointment Summary -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-muted">Current Appointment</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($appointment['service_name']); ?></h6>
                                    <p class="text-dark mb-0">
                                        <i class="bi bi-clock me-2"></i><?php echo date('g:i A', strtotime($appointment['start_time'])); ?> - 
                                        <?php 
                                            $endTime = strtotime($appointment['start_time'] . ' + ' . $appointment['duration'] . ' minutes');
                                            echo date('g:i A', $endTime);
                                        ?>
                                        <span class="mx-2">·</span>
                                        <i class="bi bi-calendar3 me-2"></i><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                    </p>
                                </div>
                                <span class="badge bg-primary"><?php echo $appointment['duration']; ?> mins</span>
                            </div>
                        </div>
                    </div>

                    <!-- Date and Therapist Selection -->
                    <div class="row g-4">
                        <!-- Calendar -->
                        <div class="col-md-5">
                            <div class="card h-100 shadow-sm calendar-card">
                                <div class="card-body">
                                    <h6 class="card-title d-flex align-items-center mb-4">
                                        <i class="bi bi-calendar-date me-2 text-primary"></i>Select New Date
                                    </h6>
                                    <div class="calendar-wrapper">
                                        <div id="datepicker-<?php echo $appointment['appointment_id']; ?>" class="datepicker-lg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Therapist Selection -->
                        <div class="col-md-7">
                            <div class="card h-100 shadow-sm therapist-section">
                                <div class="card-body">
                                    <h6 class="card-title d-flex align-items-center mb-4">
                                        <i class="bi bi-person-badge me-2 text-primary"></i>Available Therapists
                                    </h6>
                                    <div id="therapist-list-<?php echo $appointment['appointment_id']; ?>" class="therapist-list">
                                        <div class="text-center p-4">
                                            <i class="bi bi-calendar-plus display-4 text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Please select a date to view available therapists</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Time Selection -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title d-flex align-items-center mb-4">
                                        <i class="bi bi-clock me-2 text-primary"></i>Select New Time
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Preferred Start Time</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                                    <input type="time" class="form-control" id="time-select-<?php echo $appointment['appointment_id']; ?>" disabled required>
                                                </div>
                                                <div class="form-text" id="therapist-hours-<?php echo $appointment['appointment_id']; ?>">
                                                    <i class="bi bi-info-circle me-1"></i>Please select a therapist first
                                                </div>
                                                <div class="form-text">
                                                    <i class="bi bi-stopwatch me-1"></i>Service duration: 
                                                    <span id="service-duration-<?php echo $appointment['appointment_id']; ?>" class="fw-medium">90</span> minutes
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="alert alert-info d-flex align-items-center">
                                                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                                <div>
                                                    Your service will end at<br>
                                                    <span id="end-time-<?php echo $appointment['appointment_id']; ?>" class="fs-5 fw-bold">--:--</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary px-4" onclick="submitReschedule(<?php echo $appointment['appointment_id']; ?>)">
                        <i class="bi bi-check2 me-1"></i>Confirm Reschedule
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker.min.js"></script>

<style>
    /* Modal Styling */
    .modal-content {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .modal-footer {
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }

    /* Calendar Styling */
    .calendar-wrapper {
        background: #fff;
        border-radius: 0.5rem;
    }

    .datepicker-lg {
        font-size: 0.9rem;
    }

    .calendar-card {
        border: none;
        background: #fff;
    }

    /* Therapist List Styling */
    .therapist-list {
        max-height: 400px;
        overflow-y: auto;
        padding: 0.5rem;
        width: 100%;
    }

    .therapist-card {
        width: 100%;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-width: 350px;
    }

    .therapist-card:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        border-color: #dee2e6;
    }

    .therapist-card.active {
        background-color: #e3f2fd;
        border-color: #90caf9;
        box-shadow: 0 4px 6px rgba(13, 110, 253, 0.08);
    }

    .therapist-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex: 1;
    }

    .therapist-avatar {
        width: 48px;
        height: 48px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .therapist-avatar i {
        font-size: 1.5rem;
        color: #6c757d;
    }

    .therapist-details {
        flex: 1;
    }

    .therapist-name {
        color: #212529;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .therapist-time {
        display: flex;
        align-items: center;
        color: #6c757d;
        font-size: 0.9rem;
        gap: 0.5rem;
    }

    .therapist-time i {
        font-size: 0.9rem;
    }

    .therapist-arrow {
        color: #adb5bd;
        font-size: 1.25rem;
        margin-left: 1rem;
    }

    /* Update the existing styles */
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.2s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group .form-control:focus {
        border-color: #dee2e6;
        box-shadow: none;
    }

    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-radius: 0.375rem;
    }

    .alert-info {
        background-color: #e3f2fd;
        border: none;
        border-radius: 0.5rem;
    }

    .btn-light {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }

    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #e9ecef;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    .badge {
        font-weight: 500;
        padding: 0.5em 1em;
    }

    .form-text {
        color: #6c757d;
        margin-top: 0.5rem;
    }

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
    window.BASE_URL = '<?php echo BASE_URL; ?>';
    
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
        new bootstrap.Modal(document.getElementById('rescheduleModal' + appointmentId)).show();
    }

    function submitReschedule(appointmentId) {
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

    function createTherapistCard(therapist) {
        return `
            <div class="therapist-card" 
                 data-therapist-id="${therapist.id}"
                 data-date="${therapist.date}"
                 data-start-time="${therapist.start_time}"
                 data-end-time="${therapist.end_time}">
                <div class="therapist-info">
                    <div class="therapist-avatar">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="therapist-details">
                        <div class="therapist-name">${therapist.name}</div>
                        <div class="therapist-time">
                            <i class="bi bi-clock"></i>
                            <span>${formatTime(therapist.start_time)} - ${formatTime(therapist.end_time)}</span>
                        </div>
                    </div>
                </div>
                <div class="therapist-arrow">
                    <i class="bi bi-chevron-right"></i>
                </div>
            </div>
        `;
    }

    // Add time input change handler
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener for all time inputs in reschedule modals
        document.querySelectorAll('[id^="time-select-"]').forEach(timeInput => {
            timeInput.addEventListener('change', function() {
                const appointmentId = this.id.split('-')[2];
                updateEndTime(appointmentId, this.value);
            });
        });
    });
</script>

<script src="<?php echo BASE_URL; ?>/public/assets/js/dashboard.js"></script>