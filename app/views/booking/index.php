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
                <div class="d-flex justify-content-between mt-2">
                    <span class="step active">1. Select Service</span>
                    <span class="step">2. Choose Date & Time</span>
                    <span class="step">3. Confirm Booking</span>
                </div>
            </div>

            <!-- Service Selection -->
            <div class="row g-4">
                <?php foreach ($services as $service): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card h-100 <?php echo ($selectedService && $selectedService['service_id'] == $service['service_id']) ? 'border-primary' : ''; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($service['service_name']); ?></h5>
                            <p class="card-text text-muted">
                                <i class="bi bi-clock me-2"></i><?php echo $service['duration']; ?> mins
                                <span class="ms-3">₱<?php echo number_format($service['price'], 2); ?></span>
                            </p>
                            <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                            <button class="btn <?php echo ($selectedService && $selectedService['service_id'] == $service['service_id']) ? 'btn-primary' : 'btn-outline-primary'; ?> w-100 select-service" 
                                    data-service-id="<?php echo $service['service_id']; ?>">
                                <?php echo ($selectedService && $selectedService['service_id'] == $service['service_id']) ? 'Selected' : 'Select'; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="<?php echo BASE_URL; ?>/public/services" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Services
                </a>
                <button class="btn btn-primary" id="nextStep" <?php echo !$selectedService ? 'disabled' : ''; ?>>
                    Next Step<i class="bi bi-arrow-right ms-2"></i>
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
            const serviceId = this.dataset.serviceId;
            window.location.href = `<?php echo BASE_URL; ?>/public/booking?service=${serviceId}`;
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

<?php include '../app/views/templates/footer.php'; ?> 