<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Therapist Schedule Management</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAvailabilityModal">
            <i class="bi bi-plus-circle me-2"></i>Add Availability
        </button>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <select id="therapistFilter" class="form-select border-0 bg-light">
                                <option value="">Select Therapist</option>
                                <?php foreach ($therapists as $therapist): ?>
                                    <option value="<?php echo $therapist['user_id']; ?>">
                                        <?php echo htmlspecialchars($therapist['full_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary" id="prevWeek">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="currentWeek">
                                    This Week
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="nextWeek">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Weekly Calendar View -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Time</th>
                                    <?php 
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    foreach ($days as $day): 
                                        $date = new DateTime();  // Will be updated by JavaScript
                                    ?>
                                    <th class="text-center"><?php echo $day; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $timeSlots = [
                                    '09:00', '10:00', '11:00', '12:00', '13:00', 
                                    '14:00', '15:00', '16:00', '17:00', '18:00'
                                ];
                                foreach ($timeSlots as $time): 
                                ?>
                                <tr>
                                    <td class="align-middle"><?php echo $time; ?></td>
                                    <?php foreach ($days as $day): ?>
                                    <td class="text-center position-relative" style="height: 60px;">
                                        <div class="availability-slot" 
                                             data-day="<?php echo $day; ?>" 
                                             data-time="<?php echo $time; ?>">
                                        </div>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Availability Modal -->
<div class="modal fade" id="addAvailabilityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Therapist Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="availabilityForm">
                    <div class="mb-3">
                        <label class="form-label">Therapist</label>
                        <select class="form-select" name="therapist_id" required>
                            <option value="">Select Therapist</option>
                            <?php foreach ($therapists as $therapist): ?>
                                <option value="<?php echo $therapist['user_id']; ?>">
                                    <?php echo htmlspecialchars($therapist['full_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Day of Week</label>
                        <select class="form-select" name="day_of_week" required>
                            <?php foreach ($days as $day): ?>
                                <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Start Time</label>
                            <select class="form-select" name="start_time" required>
                                <?php foreach ($timeSlots as $time): ?>
                                    <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">End Time</label>
                            <select class="form-select" name="end_time" required>
                                <?php foreach ($timeSlots as $time): ?>
                                    <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" name="repeat_weekly" id="repeatWeekly">
                        <label class="form-check-label" for="repeatWeekly">Repeat Weekly</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveAvailability()">Save Availability</button>
            </div>
        </div>
    </div>
</div>

<style>
.availability-slot {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    cursor: pointer;
    transition: background-color 0.2s;
}

.availability-slot:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

.availability-slot.available {
    background-color: rgba(40, 167, 69, 0.2);
}

.availability-slot.booked {
    background-color: rgba(220, 53, 69, 0.2);
}
</style>

<!-- Add your custom scripts -->
<script src="<?php echo BASE_URL; ?>/assets/js/admin/therapist-schedule.js"></script>
