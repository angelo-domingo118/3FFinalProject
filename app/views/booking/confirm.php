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
                    <h5 class="card-title mb-4">Booking Details</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Service:</strong>
                        </div>
                        <div class="col-md-8">
                            <?php echo htmlspecialchars($service['service_name']); ?>
                            <small class="text-muted d-block">
                                Duration: <?php echo $service['duration']; ?> minutes
                            </small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Date:</strong>
                        </div>
                        <div class="col-md-8">
                            <?php echo date('l, F j, Y', strtotime($selectedDate)); ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Time:</strong>
                        </div>
                        <div class="col-md-8">
                            <?php 
                                echo date('g:i A', strtotime($selectedTime)) . ' - ' . 
                                     date('g:i A', strtotime($selectedTime) + $service['duration'] * 60);
                            ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Therapist:</strong>
                        </div>
                        <div class="col-md-8">
                            <?php echo htmlspecialchars($therapist['full_name']); ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Price:</strong>
                        </div>
                        <div class="col-md-8">
                            ₱<?php echo number_format($service['price'], 2); ?>
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
                <button type="button" class="btn btn-primary" id="confirmBookingBtn">
                    <i class="bi bi-check-circle me-2"></i>Confirm Booking
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

<!-- Login Required Modal -->
<div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginRequiredModalLabel">Login Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please log in to complete your booking. Your booking details will be saved.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="<?php echo BASE_URL; ?>/public/login" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmBookingBtn = document.getElementById('confirmBookingBtn');
    const termsCheckbox = document.getElementById('terms');
    const notesTextarea = document.getElementById('notes');

    confirmBookingBtn.addEventListener('click', function() {
        if (!termsCheckbox.checked) {
            alert('Please agree to the terms and conditions to proceed.');
            return;
        }

        // Check if user is logged in
        <?php if (!isset($_SESSION['user_id'])): ?>
            // Store booking details in session
            <?php 
            $_SESSION['booking_details'] = [
                'service_id' => $service['service_id'],
                'date' => $selectedDate,
                'time' => $selectedTime,
                'therapist_id' => $therapist['user_id'],
                'notes' => '<script>document.getElementById("notes").value</script>'
            ];
            ?>
            
            // Show login required modal
            var loginModal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
            loginModal.show();
        <?php else: ?>
            // Proceed with booking
            const bookingData = {
                service_id: <?php echo $service['service_id']; ?>,
                date: '<?php echo $selectedDate; ?>',
                time: '<?php echo $selectedTime; ?>',
                therapist_id: <?php echo $therapist['user_id']; ?>,
                notes: notesTextarea.value
            };

            // Send booking data to server
            fetch('<?php echo BASE_URL; ?>/public/booking/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(bookingData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?php echo BASE_URL; ?>/public/booking/success';
                } else {
                    alert(data.message || 'Failed to create booking. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        <?php endif; ?>
    });
});
</script>

<?php include '../app/views/templates/footer.php'; ?>
