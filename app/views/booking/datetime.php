<?php include '../app/views/templates/header.php'; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker.min.js"></script>

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
                    <h5 class="card-title mb-3">Selected Service</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2"><?php echo htmlspecialchars($service['service_name']); ?></h6>
                            <p class="text-dark mb-0">
                                <i class="bi bi-clock me-2"></i><span class="fw-medium"><?php echo $service['duration']; ?> mins</span>
                                <span class="mx-3">|</span>
                                <i class="bi bi-tag me-2"></i><span class="fw-medium">₱<?php echo number_format($service['price'], 2); ?></span>
                            </p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/public/booking" class="btn btn-outline-primary btn-sm">
                            Change Service
                        </a>
                    </div>
                </div>
            </div>

            <!-- Date and Time Selection -->
            <div class="row g-4">
                <!-- Calendar -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm calendar-card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Select Date</h5>
                            <div class="calendar-wrapper">
                                <div id="datepicker" class="datepicker-lg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time Slots with Therapists -->
                <div class="col-md-8">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Available Therapists</h5>
                            <div id="timeSlots" class="d-grid gap-2">
                                <div class="text-center p-4">
                                    <i class="bi bi-calendar-plus display-4 text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Please select a date to view available therapists</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Time Selection (Initially Hidden) -->
            <div id="timeSelectionSection" class="row mt-4 d-none">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Select Service Time</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="serviceTime" class="form-label">Preferred Start Time</label>
                                        <input type="time" class="form-control" id="serviceTime" required>
                                        <div class="form-text">
                                            Selected therapist is available from <span id="availableTimeRange"></span>
                                        </div>
                                        <div class="form-text">
                                            Service duration: <span id="serviceDuration"></span> minutes
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <small>
                                            <i class="bi bi-info-circle me-2"></i>
                                            Your service will end at <strong><span id="calculatedEndTime">--:--</span></strong>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
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

<style>
/* Calendar Styling */
.calendar-wrapper {
    background: #fff;
    border-radius: 0.5rem;
    padding: 0.5rem;
}

.datepicker-lg {
    font-size: 1rem;
    --bs-border-color: #dee2e6;
}

/* Time Slots Styling */
.time-slot {
    background-color: var(--bs-light);
    border: 1px solid var(--bs-border-color);
    padding: 1rem;
    text-align: left;
    transition: all 0.2s ease;
    border-radius: 0.5rem;
}

.time-slot:hover {
    background-color: var(--bs-light-rgb);
    border-color: var(--bs-primary);
}

.time-slot.active {
    background-color: var(--bs-primary-bg-subtle);
    border-color: var(--bs-primary);
    color: var(--bs-primary);
}

/* Time Selection Styling */
.time-selection {
    background-color: var(--bs-light);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
}

input[type="time"] {
    font-size: 1rem;
    padding: 0.5rem;
}

.alert-info {
    background-color: var(--bs-primary-bg-subtle);
    border-color: var(--bs-primary-border-subtle);
    color: var(--bs-primary);
}
</style>

<script>
const BASE_URL = '<?php echo BASE_URL; ?>';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize datepicker with enhanced options
    const datepicker = new Datepicker(document.getElementById('datepicker'), {
        minDate: new Date(),
        maxDate: new Date().setMonth(new Date().getMonth() + 2),
        daysOfWeekDisabled: [0], // Disable Sundays
        format: 'yyyy-mm-dd',
        autohide: true
    });

    // Handle date selection
    datepicker.element.addEventListener('changeDate', function(e) {
        const selectedDate = datepicker.getDate('yyyy-mm-dd');
        generateTimeSlots(selectedDate);
    });

    function generateTimeSlots(date) {
        console.log('Fetching slots for date:', date);
        const url = `${BASE_URL}/public/booking/getAvailableSlots?date=${date}&service_id=<?php echo $service['service_id']; ?>`;
        console.log('Fetch URL:', url);
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Received data:', data);
                const timeSlotsContainer = document.getElementById('timeSlots');
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const slots = data.slots || [];
                if (slots.length === 0) {
                    timeSlotsContainer.innerHTML = `
                        <div class="text-center p-4">
                            <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                            <p class="text-muted mb-0">No available therapists for the selected date</p>
                        </div>
                    `;
                    return;
                }
                
                const timeSlotsHtml = slots.map(slot => `
                    <button class="btn time-slot" 
                            data-therapist-id="${slot.therapist_id}"
                            data-start-time="${slot.start_time}"
                            data-end-time="${slot.end_time}"
                            data-service-duration="${slot.service_duration}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-clock me-2"></i>${slot.formatted_start_time} - ${slot.formatted_end_time}
                            </div>
                            <div class="text-end">
                                <small class="d-block text-dark">with</small>
                                <span class="fw-medium">${slot.therapist_name}</span>
                            </div>
                        </div>
                    </button>
                `).join('');
                
                timeSlotsContainer.innerHTML = timeSlotsHtml;
                
                // Add click handlers for time slots
                document.querySelectorAll('.time-slot').forEach(button => {
                    button.addEventListener('click', function() {
                        document.querySelectorAll('.time-slot').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Show time selection section
                        const timeSelectionSection = document.getElementById('timeSelectionSection');
                        timeSelectionSection.classList.remove('d-none');
                        
                        // Update available time range
                        const startTime = this.dataset.startTime;
                        const endTime = this.dataset.endTime;
                        const serviceDuration = this.dataset.serviceDuration;
                        
                        document.getElementById('availableTimeRange').textContent = 
                            `${formatTime(startTime)} to ${formatTime(endTime)}`;
                        document.getElementById('serviceDuration').textContent = serviceDuration;
                        
                        // Set min/max time for the time input
                        const timeInput = document.getElementById('serviceTime');
                        timeInput.min = startTime;
                        // Calculate max time by subtracting service duration from end time
                        const maxTime = subtractMinutes(endTime, serviceDuration);
                        timeInput.max = maxTime;
                        timeInput.value = ''; // Reset time input
                        
                        // Reset end time display and disable next button
                        document.getElementById('calculatedEndTime').textContent = '--:--';
                        document.getElementById('nextStep').disabled = true;
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching slots:', error);
                const timeSlotsContainer = document.getElementById('timeSlots');
                timeSlotsContainer.innerHTML = `
                    <div class="text-center p-4 text-danger">
                        <i class="bi bi-exclamation-circle display-4 mb-3"></i>
                        <p class="mb-0">Error loading time slots. Please try again.</p>
                    </div>
                `;
            });
    }

    // Add time input handler
    document.getElementById('serviceTime').addEventListener('change', function() {
        const selectedSlot = document.querySelector('.time-slot.active');
        if (!selectedSlot) return;

        const startTime = this.value;
        const serviceDuration = parseInt(selectedSlot.dataset.serviceDuration);
        const endTime = addMinutes(startTime, serviceDuration);
        const slotEndTime = selectedSlot.dataset.endTime;

        document.getElementById('calculatedEndTime').textContent = formatTime(endTime);

        // Validate if the calculated end time is within therapist availability
        const isValid = isTimeWithinRange(endTime, startTime, slotEndTime);
        document.getElementById('nextStep').disabled = !isValid;

        if (!isValid) {
            alert('The selected time would exceed the therapist\'s availability. Please select an earlier time.');
            this.value = '';
            document.getElementById('calculatedEndTime').textContent = '--:--';
        }
    });

    // Helper functions
    function formatTime(timeStr) {
        return new Date('2000-01-01T' + timeStr).toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    function addMinutes(time, minutes) {
        const date = new Date('2000-01-01T' + time);
        date.setMinutes(date.getMinutes() + parseInt(minutes));
        return date.toTimeString().slice(0, 5);
    }

    function subtractMinutes(time, minutes) {
        const date = new Date('2000-01-01T' + time);
        date.setMinutes(date.getMinutes() - parseInt(minutes));
        return date.toTimeString().slice(0, 5);
    }

    function isTimeWithinRange(endTime, startTime, maxEndTime) {
        const end = new Date('2000-01-01T' + endTime);
        const start = new Date('2000-01-01T' + startTime);
        const max = new Date('2000-01-01T' + maxEndTime);
        return end <= max && start >= start;
    }

    // Update next step handler
    document.getElementById('nextStep').addEventListener('click', function() {
        const selectedDate = datepicker.getDate('yyyy-mm-dd');
        const selectedSlot = document.querySelector('.time-slot.active');
        const selectedTime = document.getElementById('serviceTime').value;
        const therapistId = selectedSlot.dataset.therapistId;
        
        window.location.href = `${BASE_URL}/public/booking/confirm?` + 
            `service=<?php echo $service['service_id']; ?>` +
            `&date=${selectedDate}` +
            `&time=${encodeURIComponent(selectedTime)}` +
            `&therapist=${therapistId}`;
    });
});
</script>

<?php include '../app/views/templates/footer.php'; ?>