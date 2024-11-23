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
                <div class="col-md-7">
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
                <div class="col-md-5">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Available Time Slots</h5>
                            <div id="timeSlots" class="d-grid gap-2">
                                <div class="text-center p-4">
                                    <i class="bi bi-calendar-plus display-4 text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Please select a date to view available time slots</p>
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

.datepicker-header {
    padding: 0.75rem;
    background: transparent;
    border-bottom: 1px solid var(--bs-border-color);
}

.datepicker-cell {
    border-radius: 0.25rem;
}

.datepicker-cell:not(.disabled):hover {
    background-color: var(--bs-primary-bg-subtle);
}

.datepicker-cell.selected,
.datepicker-cell.selected:hover {
    background-color: var(--bs-primary) !important;
    color: white !important;
    font-weight: 600;
}

.datepicker-cell.disabled {
    color: #ccc;
}

.datepicker-view {
    padding: 0.5rem;
}

/* Time Slots Styling */
.time-slot {
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
    font-weight: 500;
    border: 1px solid var(--bs-border-color);
}

.time-slot:hover {
    background-color: var(--bs-primary-bg-subtle);
    border-color: var(--bs-primary-border-subtle);
}

.time-slot.active {
    background-color: var(--bs-primary);
    color: white;
    border-color: var(--bs-primary);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Therapist Card Enhancements */
.therapist-card {
    transition: all 0.2s ease;
    border: 2px solid transparent;
    border-radius: 0.75rem;
    cursor: pointer;
}

.therapist-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-color: var(--bs-primary-border-subtle);
}

.therapist-card.selected {
    border-color: var(--bs-primary);
    background-color: var(--bs-primary-bg-subtle);
}

.therapist-avatar {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background-color: var(--bs-primary-bg-subtle);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.therapist-card.selected .therapist-avatar {
    background-color: var(--bs-primary);
}

.therapist-card.selected .therapist-avatar i {
    color: white;
}

.select-indicator {
    padding-top: 1rem;
    border-top: 1px solid var(--bs-border-color);
}

.select-indicator .selected-text {
    display: none;
    color: var(--bs-primary);
    font-weight: 500;
}

.select-indicator .select-text {
    color: var(--bs-secondary);
    font-size: 0.875rem;
}

.therapist-card.selected .select-indicator .selected-text {
    display: inline-block;
}

.therapist-card.selected .select-indicator .select-text {
    display: none;
}

/* Add animation for selection */
@keyframes selectPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.therapist-card.selected {
    animation: selectPulse 0.3s ease-in-out;
}

.calendar-card {
    max-width: 380px;
    margin: 0 auto;
}

.calendar-wrapper {
    background: #fff;
    border-radius: 0.5rem;
    padding: 0.5rem;
}

.datepicker-lg {
    font-size: 1rem;
}

/* Ensure calendar cells are properly sized */
.datepicker-grid {
    width: 100%;
}

.datepicker-cell {
    height: 40px;
    width: 40px;
}

/* Adjust column width for better calendar fit */
@media (min-width: 768px) {
    .col-md-7 {
        width: auto;
        flex: 0 0 auto;
    }
    
    .col-md-5 {
        flex: 1;
    }
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
        autohide: true,
        format: 'yyyy-mm-dd',
        buttonClass: 'btn',
        todayHighlight: true,
        prevArrow: '<i class="bi bi-chevron-left"></i>',
        nextArrow: '<i class="bi bi-chevron-right"></i>',
        container: '.calendar-wrapper'
    });

    // Add date change event handler
    datepicker.element.addEventListener('changeDate', function(e) {
        const selectedDate = datepicker.getDate('yyyy-mm-dd');
        generateTimeSlots(selectedDate);
    });

    // Update time slot generation
    function generateTimeSlots(date) {
        console.log('Fetching slots for date:', date);
        const url = `${BASE_URL}/public/booking/getAvailableSlots?date=${date}&service_id=<?php echo $service['service_id']; ?>`;
        console.log('Fetch URL:', url);
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
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
                            <p class="text-muted mb-0">No available time slots for the selected date</p>
                        </div>
                    `;
                    return;
                }
                
                const timeSlotsHtml = slots.map(slot => `
                    <button class="btn time-slot" 
                            data-therapist-id="${slot.therapist_id}"
                            data-time="${slot.start_time}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-clock me-2"></i>${formatTime(slot.start_time)}
                            </div>
                            <div class="text-end">
                                <small class="d-block text-muted">with</small>
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
                        document.getElementById('nextStep').disabled = false;
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

    function formatTime(timeStr) {
        return new Date('2000-01-01T' + timeStr).toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    // Update next step handler
    document.getElementById('nextStep').addEventListener('click', function() {
        const selectedDate = datepicker.getDate('yyyy-mm-dd');
        const selectedSlot = document.querySelector('.time-slot.active');
        const therapistId = selectedSlot.dataset.therapistId;
        const selectedTime = selectedSlot.dataset.time;
        
        window.location.href = `${BASE_URL}/public/booking/confirm?` + 
            `service=<?php echo $service['service_id']; ?>` +
            `&date=${selectedDate}` +
            `&time=${encodeURIComponent(selectedTime)}` +
            `&therapist=${therapistId}`;
    });
});
</script>

<?php include '../app/views/templates/footer.php'; ?> 