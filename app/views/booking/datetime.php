<?php include '../app/views/templates/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Book an Appointment</h2>
            
            <!-- Progress Steps -->
            <div class="booking-progress mb-5">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 66%;" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span class="step">1. Select Service</span>
                    <span class="step active">2. Choose Date & Time</span>
                    <span class="step">3. Confirm Booking</span>
                </div>
            </div>

            <!-- Service Summary -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Selected Service</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1"><?php echo htmlspecialchars($service['service_name']); ?></h6>
                            <p class="text-muted mb-0">
                                <i class="bi bi-clock me-2"></i><?php echo $service['duration']; ?> mins
                                <span class="ms-3">₱<?php echo number_format($service['price'], 2); ?></span>
                            </p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/public/booking" class="btn btn-outline-primary btn-sm">Change</a>
                    </div>
                </div>
            </div>

            <!-- Date and Time Selection -->
            <div class="row">
                <!-- Calendar -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Select Date</h5>
                            <div id="datepicker"></div>
                        </div>
                    </div>
                </div>

                <!-- Time Slots -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Available Time Slots</h5>
                            <div id="timeSlots" class="d-grid gap-2">
                                <!-- Time slots will be populated by JavaScript -->
                                <p class="text-muted text-center">Please select a date first</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Therapist Selection -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Select Therapist</h5>
                    <div class="row g-4">
                        <?php foreach ($therapists as $therapist): ?>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo htmlspecialchars($therapist['full_name']); ?></h6>
                                    <p class="card-text small text-muted">Experienced in <?php echo htmlspecialchars($service['service_type']); ?> treatments</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="therapist" 
                                               value="<?php echo $therapist['user_id']; ?>" 
                                               id="therapist<?php echo $therapist['user_id']; ?>">
                                        <label class="form-check-label" for="therapist<?php echo $therapist['user_id']; ?>">
                                            Select Therapist
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="<?php echo BASE_URL; ?>/public/booking?service=<?php echo $service['service_id']; ?>" 
                   class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <button class="btn btn-primary" id="nextStep" disabled>
                    Next Step<i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize datepicker
    const datepicker = new Datepicker(document.getElementById('datepicker'), {
        minDate: new Date(),
        maxDate: new Date().setMonth(new Date().getMonth() + 2), // 2 months ahead
        daysOfWeekDisabled: [0], // Disable Sundays
        autohide: true
    });

    // Handle date selection
    datepicker.element.addEventListener('changeDate', function(e) {
        // Here you would typically make an AJAX call to get available time slots
        // For now, we'll simulate some time slots
        const timeSlots = [
            '09:00 AM', '10:00 AM', '11:00 AM',
            '02:00 PM', '03:00 PM', '04:00 PM'
        ];
        
        const timeSlotsHtml = timeSlots.map(time => `
            <button class="btn btn-outline-primary time-slot">
                ${time}
            </button>
        `).join('');
        
        document.getElementById('timeSlots').innerHTML = timeSlotsHtml;
    });

    // Enable next button when both date/time and therapist are selected
    function checkSelections() {
        const hasTime = document.querySelector('.time-slot.active');
        const hasTherapist = document.querySelector('input[name="therapist"]:checked');
        document.getElementById('nextStep').disabled = !(hasTime && hasTherapist);
    }

    // Handle time slot selection
    document.getElementById('timeSlots').addEventListener('click', function(e) {
        if (e.target.classList.contains('time-slot')) {
            document.querySelectorAll('.time-slot').forEach(btn => btn.classList.remove('active'));
            e.target.classList.add('active');
            checkSelections();
        }
    });

    // Handle therapist selection
    document.querySelectorAll('input[name="therapist"]').forEach(radio => {
        radio.addEventListener('change', checkSelections);
    });

    // Handle next step
    document.getElementById('nextStep').addEventListener('click', function() {
        const selectedDate = datepicker.getDate('yyyy-mm-dd');
        const selectedTime = document.querySelector('.time-slot.active').textContent.trim();
        const selectedTherapist = document.querySelector('input[name="therapist"]:checked').value;
        
        window.location.href = `<?php echo BASE_URL; ?>/public/booking/confirm?` + 
            `service=<?php echo $service['service_id']; ?>` +
            `&date=${selectedDate}` +
            `&time=${encodeURIComponent(selectedTime)}` +
            `&therapist=${selectedTherapist}`;
    });
});
</script>

<?php include '../app/views/templates/footer.php'; ?> 