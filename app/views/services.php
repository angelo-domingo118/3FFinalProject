<?php include 'templates/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Filters</h5>
                    <form id="filterForm">
                        <!-- Service Type Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Service Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="type[]" value="massage">
                                <label class="form-check-label">Massage Therapy</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="type[]" value="facial">
                                <label class="form-check-label">Facial Treatments</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="type[]" value="body">
                                <label class="form-check-label">Body Treatments</label>
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Price Range</label>
                            <div class="range-slider">
                                <input type="range" class="form-range" id="priceRange" min="1000" max="3000" step="100">
                                <div class="range-values d-flex justify-content-between">
                                    <span>₱1,000</span>
                                    <span id="priceValue">₱2,000</span>
                                    <span>₱3,000</span>
                                </div>
                            </div>
                        </div>

                        <!-- Duration Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Duration</label>
                            <select class="form-select" name="duration">
                                <option value="">All Durations</option>
                                <option value="30">30 minutes</option>
                                <option value="60">60 minutes</option>
                                <option value="90">90 minutes</option>
                                <option value="120">120 minutes</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Services List -->
        <div class="col-lg-9">
            <!-- Sort Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Our Services</h2>
                <div class="d-flex align-items-center">
                    <label class="me-2">Sort by:</label>
                    <select class="form-select" style="width: auto;" id="sortSelect">
                        <option value="popular">Most Popular</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="duration">Duration</option>
                    </select>
                </div>
            </div>

            <!-- Services Grid -->
            <div class="row g-4">
                <?php foreach ($services as $service): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card h-100 shadow-sm">
                        <div class="service-image-wrapper text-center bg-light p-4">
                            <?php
                            $icon = match($service['service_type']) {
                                'massage' => 'bi-hand-index-thumb',
                                'facial' => 'bi-stars',
                                'body' => 'bi-heart-pulse',
                                default => 'bi-spa'
                            };
                            ?>
                            <i class="bi <?php echo $icon; ?> display-1 text-primary"></i>
                            <?php if ($service['is_popular']): ?>
                                <span class="service-badge">Popular</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($service['service_name']); ?></h5>
                                <span class="service-price">
                                    <span class="currency">₱</span><?php echo number_format($service['price'], 2); ?>
                                </span>
                            </div>
                            <p class="card-text text-muted">
                                <i class="bi bi-clock me-2"></i><?php echo htmlspecialchars($service['duration']); ?> mins
                            </p>
                            <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="<?php echo BASE_URL; ?>/public/booking?service=<?php echo $service['service_id']; ?>" 
                               class="btn btn-primary w-100">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Price range slider
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    
    priceRange.addEventListener('input', function() {
        priceValue.textContent = `₱${this.value.toLocaleString()}`;
    });

    // Sort functionality
    const sortSelect = document.getElementById('sortSelect');
    sortSelect.addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('sort', this.value);
        window.location = url;
    });

    // Filter form submission
    const filterForm = document.getElementById('filterForm');
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const params = new URLSearchParams(formData);
        window.location = `${window.location.pathname}?${params.toString()}`;
    });
});
</script>

<?php include 'templates/footer.php'; ?> 