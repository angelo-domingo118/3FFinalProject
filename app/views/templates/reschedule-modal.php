<!-- Reschedule Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rescheduleModalLabel">Reschedule Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Current Appointment Info -->
                <div class="current-appointment mb-4">
                    <h6>Current Appointment</h6>
                    <div id="currentAppointmentInfo" class="mb-3">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>

                <h6>Select New Schedule</h6>
                <form id="rescheduleForm">
                    <input type="hidden" id="appointmentId" name="appointmentId">
                    
                    <div class="row g-3">
                        <!-- Date Selection -->
                        <div class="col-md-6">
                            <label for="newDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="newDate" name="newDate" required>
                        </div>

                        <!-- Time Selection -->
                        <div class="col-md-6">
                            <label for="newTime" class="form-label">Time</label>
                            <select class="form-control" id="newTime" name="newTime" required>
                                <option value="">Select time</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmReschedule">Confirm Reschedule</button>
            </div>
        </div>
    </div>
</div>

<style>
.current-appointment {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.current-appointment p {
    margin-bottom: 0.5rem;
}

.current-appointment p:last-child {
    margin-bottom: 0;
}
</style> 