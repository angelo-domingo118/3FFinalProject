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
                                    <p class="mb-0 text-dark">
                                        <i class="bi bi-clock me-1"></i><?php 
                                            echo date('g:i A', strtotime($selectedTime)); 
                                            // Calculate end time
                                            $endTime = date('g:i A', strtotime($selectedTime . ' + ' . $service['duration'] . ' minutes'));
                                            echo ' - ' . $endTime;
                                        ?>
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

            <!-- Payment Options & Promo Code -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Payment Details</h5>
                    <div class="row g-4">
                        <!-- Payment Method Selection -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-credit-card fs-4 me-3 text-primary"></i>
                                <div class="w-100">
                                    <h6 class="mb-3">Payment Method</h6>
                                    <div class="payment-methods">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="paymentMethod" id="cashPayment" value="cash" checked>
                                            <label class="form-check-label" for="cashPayment">
                                                <i class="bi bi-cash me-2"></i>Cash
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="paymentMethod" id="cardPayment" value="credit_card">
                                            <label class="form-check-label" for="cardPayment">
                                                <i class="bi bi-credit-card-2-front me-2"></i>Credit Card
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentMethod" id="paypalPayment" value="paypal">
                                            <label class="form-check-label" for="paypalPayment">
                                                <i class="bi bi-paypal me-2"></i>PayPal
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Promo Code Section -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-tag fs-4 me-3 text-primary"></i>
                                <div class="w-100">
                                    <h6 class="mb-3">Promo Code</h6>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="promoCode" placeholder="Enter promo code">
                                        <button class="btn btn-outline-primary" type="button" id="applyPromoBtn">Apply</button>
                                    </div>
                                    <div id="promoMessage" class="form-text mt-2"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="col-12">
                            <div class="border-top pt-3 mt-2">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Original Price:</span>
                                    <span class="original-price">₱<?php echo number_format($service['price'], 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 discount-row" style="display: none !important;">
                                    <span>Discount:</span>
                                    <span class="discount-amount text-success">-₱0.00</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total Amount:</span>
                                    <span class="total-amount">₱<?php echo number_format($service['price'], 2); ?></span>
                                </div>
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
// Define BASE_URL for JavaScript
const BASE_URL = '<?php echo BASE_URL; ?>';

document.addEventListener('DOMContentLoaded', function() {
    const confirmBookingBtn = document.getElementById('confirmBookingBtn');
    const termsCheckbox = document.getElementById('terms');
    const notesTextarea = document.getElementById('notes');

    // Add promo code handling
    const promoCodeInput = document.getElementById('promoCode');
    const applyPromoBtn = document.getElementById('applyPromoBtn');
    const promoMessage = document.getElementById('promoMessage');
    const discountRow = document.querySelector('.discount-row');
    const originalPrice = <?php echo $service['price']; ?>;
    let discountedPrice = originalPrice;
    let activePromoCode = null;

    applyPromoBtn.addEventListener('click', function() {
        const promoCode = promoCodeInput.value.trim().toUpperCase();
        
        if (!promoCode) {
            showPromoMessage('Please enter a promo code', 'text-danger');
            return;
        }

        // Send AJAX request to validate promo code
        fetch(`${BASE_URL}/public/booking/validate-promo`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                promo_code: promoCode,
                service_id: <?php echo $service['service_id']; ?>,
                amount: originalPrice
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const discount = (originalPrice * data.discount_percent) / 100;
                discountedPrice = originalPrice - discount;
                activePromoCode = promoCode;
                
                // Update UI
                discountRow.style.display = 'flex';
                document.querySelector('.discount-amount').textContent = 
                    `-₱${discount.toFixed(2)}`;
                document.querySelector('.total-amount').textContent = 
                    `₱${discountedPrice.toFixed(2)}`;
                
                showPromoMessage(`${data.discount_percent}% discount applied successfully!`, 'text-success');
                
                // Disable input and button after successful application
                promoCodeInput.disabled = true;
                applyPromoBtn.disabled = true;
            } else {
                showPromoMessage(data.message || 'Invalid promo code', 'text-danger');
                resetPromoCode();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showPromoMessage('Error validating promo code', 'text-danger');
            resetPromoCode();
        });
    });

    function showPromoMessage(message, className) {
        promoMessage.textContent = message;
        promoMessage.className = `form-text mt-2 ${className}`;
    }

    function resetPromoCode() {
        discountedPrice = originalPrice;
        activePromoCode = null;
        discountRow.style.display = 'none';
        document.querySelector('.total-amount').textContent = 
            `₱${originalPrice.toFixed(2)}`;
        promoCodeInput.disabled = false;
        applyPromoBtn.disabled = false;
    }

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
                notes: notesTextarea.value,
                payment_method: document.querySelector('input[name="paymentMethod"]:checked').value,
                promo_code: activePromoCode,
                original_amount: originalPrice,
                discount_amount: activePromoCode ? (originalPrice - discountedPrice) : 0,
                final_amount: discountedPrice
            };

            // Send booking data to server
            fetch(`${BASE_URL}/public/booking/process`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(bookingData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Use SweetAlert for post-booking dialog
                    Swal.fire({
                        title: 'Booking Submitted!',
                        html: 'Your booking request has been submitted successfully.<br><br>' +
                              'Please wait for approval from our staff. You will receive a confirmation via email and you can also check the status in your dashboard.',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'View in Dashboard',
                        cancelButtonText: 'Book Another Service'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to user dashboard
                            window.location.href = '<?php echo BASE_URL; ?>/public/dashboard';
                        } else {
                            // Redirect to services page
                            window.location.href = '<?php echo BASE_URL; ?>/public/services';
                        }
                    });
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

<!-- Include SweetAlert2 for dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include '../app/views/templates/footer.php'; ?>
