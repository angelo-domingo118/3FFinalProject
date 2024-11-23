<?php include '../app/views/templates/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Book an Appointment</h2>
            
            <!-- Progress Steps -->
            <div class="booking-progress mb-5">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <div class="step active">
                        <div class="step-icon">
                            <i class="bi bi-1-circle-fill"></i>
                        </div>
                        <span class="step-label">Select Service</span>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="bi bi-2-circle"></i>
                        </div>
                        <span class="step-label">Choose Date & Time</span>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="bi bi-3-circle"></i>
                        </div>
                        <span class="step-label">Confirm Booking</span>
                    </div>
                </div>
            </div>

            <!-- Service Selection -->
            <div class="row g-4">
                <?php foreach ($services as $service): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card h-100 <?php echo ($selectedService && $selectedService['service_id'] == $service['service_id']) ? 'selected' : ''; ?>"
                         data-service-id="<?php echo $service['service_id']; ?>">
                        <div class="service-image p-4 text-center bg-light rounded-top">
                            <?php
                            $icon = match($service['service_type']) {
                                'massage' => 'bi-hand-index-thumb',
                                'facial' => 'bi-stars',
                                'body' => 'bi-heart-pulse',
                                default => 'bi-spa'
                            };
                            ?>
                            <i class="bi <?php echo $icon; ?> display-4 text-primary"></i>
                            <?php if ($service['is_popular']): ?>
                            <span class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-primary">Popular</span>
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">
                                    <?php echo htmlspecialchars($service['service_name']); ?>
                                </h5>
                                <?php if ($selectedService && $selectedService['service_id'] == $service['service_id']): ?>
                                <span class="selected-check">
                                    <i class="bi bi-check-circle-fill text-primary"></i>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="service-meta d-flex justify-content-between align-items-center mb-3">
                                <span class="duration-badge">
                                    <i class="bi bi-clock me-1"></i>
                                    <span class="fw-semibold"><?php echo $service['duration']; ?></span> mins
                                </span>
                                <span class="price-badge">
                                    <i class="bi bi-tag me-1"></i>
                                    <span class="fw-bold">₱<?php echo number_format($service['price'], 2); ?></span>
                                </span>
                            </div>
                            <p class="card-text">
                                <?php echo htmlspecialchars($service['description']); ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 p-4 pt-0">
                            <button class="btn <?php echo ($selectedService && $selectedService['service_id'] == $service['service_id']) ? 'btn-primary' : 'btn-outline-primary'; ?> w-100 rounded-pill select-service"
                                    data-service-id="<?php echo $service['service_id']; ?>">
                                <?php if ($selectedService && $selectedService['service_id'] == $service['service_id']): ?>
                                <i class="bi bi-check-circle me-2"></i>Selected
                                <?php else: ?>
                                <i class="bi bi-plus-circle me-2"></i>Select Service
                                <?php endif; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between mt-5">
                <a href="<?php echo BASE_URL; ?>/public/services" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back to Services
                </a>
                <button class="btn btn-primary px-4" id="nextStep" <?php echo !$selectedService ? 'disabled' : ''; ?>>
                    Continue to Date & Time<i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.select-service');
    const nextButton = document.getElementById('nextStep');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-service-id');
            if (serviceId) {
                window.location.href = `<?php echo BASE_URL; ?>/public/booking?service=${serviceId}`;
            }
        });
    });

    nextButton.addEventListener('click', function() {
        const selectedService = <?php echo $selectedService ? $selectedService['service_id'] : 'null'; ?>;
        if (selectedService) {
            window.location.href = `<?php echo BASE_URL; ?>/public/booking/datetime?service=${selectedService}`;
        }
    });
});
</script>

<style>
/* Enhanced Progress Steps */
.booking-progress {
    position: relative;
    padding: 1rem 0;
}

.booking-progress .progress {
    position: absolute;
    top: 2.5rem;
    width: 100%;
    z-index: 1;
}

.step {
    text-align: center;
    position: relative;
    z-index: 2;
    background: white;
    padding: 0 1rem;
}

.step-icon {
    width: 2.5rem;
    height: 2.5rem;
    margin: 0 auto 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 50%;
}

.step-icon i {
    font-size: 1.5rem;
}

.step.active .step-icon i {
    color: var(--bs-primary);
}

.step-label {
    font-size: 0.875rem;
    color: #6c757d;
    display: block;
    margin-top: 0.5rem;
}

.step.active .step-label {
    color: var(--bs-primary);
    font-weight: 500;
}

/* Enhanced Service Cards */
.service-card {
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid transparent;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.service-card.selected {
    border-color: var(--bs-primary);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.15);
}

.service-image {
    position: relative;
    overflow: hidden;
}

.selected-check {
    font-size: 1.25rem;
    animation: checkmark 0.3s ease-in-out;
}

@keyframes checkmark {
    0% { transform: scale(0); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

/* Enhanced Badges */
.duration-badge,
.price-badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.duration-badge {
    color: #2c7be5;
    background-color: #e8f1fc;
}

.price-badge {
    color: #00864e;
    background-color: #e6f4ed;
}

/* Navigation Buttons */
.btn {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

#nextStep:disabled {
    cursor: not-allowed;
    opacity: 0.65;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .booking-progress .step-label {
        font-size: 0.75rem;
    }
    
    .service-card {
        margin-bottom: 1rem;
    }
}
</style>

<?php include '../app/views/templates/footer.php'; ?> 