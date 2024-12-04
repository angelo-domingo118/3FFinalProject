<?php include_once '../app/views/templates/header.php'; ?>

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid py-4">
    <h2 class="mb-4">Manage Bookings</h2>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3" method="GET">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All</option>
                        <option value="pending" <?php echo isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo isset($_GET['status']) && $_GET['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="completed" <?php echo isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="canceled" <?php echo isset($_GET['status']) && $_GET['status'] === 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" 
                           value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
                </div>
                <div class="col-md-3">
                    <label for="therapist" class="form-label">Therapist</label>
                    <select class="form-select" id="therapist" name="therapist_id">
                        <option value="">All Therapists</option>
                        <?php foreach ($therapists as $therapist): ?>
                            <option value="<?php echo $therapist['user_id']; ?>" 
                                    <?php echo isset($_GET['therapist_id']) && $_GET['therapist_id'] == $therapist['user_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($therapist['full_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Therapist</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No appointments found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?php echo $appointment['appointment_id']; ?></td>
                                    <td><?php echo htmlspecialchars($appointment['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['therapist_name']); ?></td>
                                    <td><?php echo date('F j, Y', strtotime($appointment['appointment_date'])); ?></td>
                                    <td>
                                        <?php echo date('g:i A', strtotime($appointment['start_time'])); ?> - 
                                        <?php echo date('g:i A', strtotime($appointment['end_time'])); ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $statusBadges[$appointment['status']]; ?>">
                                            <?php echo ucfirst($appointment['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($appointment['status'] !== 'canceled' && $appointment['status'] !== 'completed'): ?>
                                            <div class="btn-group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-primary"
                                                        onclick="handleReschedule(<?php echo $appointment['appointment_id']; ?>)">
                                                    Reschedule
                                                </button>
                                                <?php if ($appointment['status'] === 'pending'): ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-success"
                                                            onclick="updateStatus(<?php echo $appointment['appointment_id']; ?>, 'confirmed')">
                                                        Confirm
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($appointment['status'] === 'confirmed'): ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-success"
                                                            onclick="updateStatus(<?php echo $appointment['appointment_id']; ?>, 'completed')">
                                                        Complete
                                                    </button>
                                                <?php endif; ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        onclick="cancelAppointment(<?php echo $appointment['appointment_id']; ?>)">
                                                    Cancel
                                                </button>
                                            </div>
                                        <?php endif; ?>
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

<!-- Include the reschedule modal -->
<?php include_once '../app/views/templates/reschedule-modal.php'; ?>

<!-- Include SweetAlert2 for better alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include the bookings functionality -->
<script src="/cit17-final-project/public/assets/js/bookings.js"></script>

<?php include_once '../app/views/templates/footer.php'; ?>