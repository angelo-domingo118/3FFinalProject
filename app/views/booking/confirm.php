<?php include '../app/views/templates/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Book an Appointment</h2>
            
            <!-- Progress Steps -->
            <div class="booking-progress mb-5">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span class="step">1. Select Service</span>
                    <span class="step">2. Choose Date & Time</span>
                    <span class="step active">3. Confirm Booking</span>
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Booking Summary</h5>
                    <div class="row g-4">
                        <!-- Service Details -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-bag-check fs-4 me-3 text-primary"></i>
                                <div>
                                    <h6 class="mb-1">Service</h6>
                                    <p class="mb-0 fw-medium"><?php echo htmlspecialchars($service['service_name']); ?></p>
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-clock-history me-1"></i><?php echo $service['duration']; ?> mins
                                        <span class="mx-2">|</span>
                                        <i class="bi bi-tag me-1"></i>₱<?php echo number_format($service['price'], 2); ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-calendar-check fs-4 me-3 text-primary"></i>
                                <div>
                                    <h6 class="mb-1">Date & Time</h6>
                                    <p class="mb-0 fw-medium"><?php echo date('l, F j, Y', strtotime($selectedDate)); ?></p>
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-clock me-1"></i><?php echo date('g:i A', strtotime($selectedTime)); ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Therapist -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-person-check fs-4 me-3 text-primary"></i>
                                <div>
                                    <h6 class="mb-1">Therapist</h6>
                                    <p class="mb-0 fw-medium"><?php echo htmlspecialchars($therapist['full_name']); ?></p>
                                    <p class="text-muted mb-0">Professional Therapist</p>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Notes -->
                        <div class="col-12 mt-4">
                            <div class="form-floating">
                                <textarea class="form-control" id="notes" style="height: 100px" placeholder="Any special requests or notes?"></textarea>
                                <label for="notes">Special Requests or Notes (Optional)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between">
                <a href="<?php echo BASE_URL; ?>/public/booking/datetime?service=<?php echo $service['service_id']; ?>" 
                   class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <button type="button" id="confirmBooking" class="btn btn-primary" disabled>
                    Confirm Booking<i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Booking Terms:</h6>
                <ul>
                    <li>Please arrive 10 minutes before your appointment time</li>
                    <li>Cancellations must be made at least 24 hours in advance</li>
                    <li>Late arrivals may result in reduced service time</li>
                    <li>Payment is required at the time of service</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const termsCheckbox = document.getElementById('terms');
    const confirmButton = document.getElementById('confirmBooking');
    
    // Enable/disable confirm button based on terms checkbox
    termsCheckbox.addEventListener('change', function() {
        confirmButton.disabled = !this.checked;
    });
    
    // Handle booking confirmation
    confirmButton.addEventListener('click', function() {
        const notes = document.getElementById('notes').value;
        
        // Create booking data
        const bookingData = {
            service_id: <?php echo $service['service_id']; ?>,
            therapist_id: <?php echo $therapist['user_id']; ?>,
            date: '<?php echo $selectedDate; ?>',
            time: '<?php echo $selectedTime; ?>',
            notes: notes
        };
        
        // Send booking request
        fetch(`${BASE_URL}/public/booking/process`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(bookingData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `${BASE_URL}/public/booking/success?id=${data.booking_id}`;
            } else {
                alert(data.error || 'Failed to create booking. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});
</script>

<?php include '../app/views/templates/footer.php'; ?>
