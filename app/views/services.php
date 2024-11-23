<?php include 'templates/header.php'; ?>

<!-- Hero Section -->
<div class="services-hero py-5 mb-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Our Services</h1>
                <p class="lead mb-4">Discover our range of premium wellness treatments tailored for your relaxation and rejuvenation</p>
                <div class="search-wrapper">
                    <form action="" method="GET" id="searchForm" class="w-100">
                        <div class="input-group">
                            <input type="search" class="form-control form-control-lg" 
                                   name="search" id="serviceSearch"
                                   placeholder="Search services..." 
                                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button class="btn btn-light" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Filters</h5>
                        <button class="btn btn-link text-decoration-none p-0" id="clearFilters">
                            Clear All
                        </button>
                    </div>
                    
                    <form id="filterForm">
                        <!-- Service Type Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-medium mb-3">Service Type</label>
                            <div class="d-flex flex-column gap-2">
                                <?php
                                $types = ['massage' => 'Massage Therapy', 
                                         'facial' => 'Facial Treatments',
                                         'body' => 'Body Treatments'];
                                foreach ($types as $value => $label):
                                ?>
                                <div class="form-check custom-checkbox">
                                    <input class="form-check-input" type="checkbox" 
                                           name="type[]" value="<?php echo $value; ?>" 
                                           id="type_<?php echo $value; ?>">
                                    <label class="form-check-label" for="type_<?php echo $value; ?>">
                                        <?php echo $label; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="form-label fw-medium mb-3">
                                Price Range
                            </label>
                            <!-- Manual input fields -->
                            <div class="d-flex gap-2 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" id="priceMinInput" 
                                           min="0" max="5000" step="100" value="0" placeholder="Min">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" id="priceMaxInput" 
                                           min="0" max="5000" step="100" value="5000" placeholder="Max">
                                </div>
                            </div>
                            <!-- Slider -->
                            <div class="price-slider-wrapper position-relative mb-2">
                                <div class="multi-range">
                                    <input type="range" id="priceMin" class="range-min" min="0" max="5000" value="0" step="100">
                                    <input type="range" id="priceMax" class="range-max" min="0" max="5000" value="5000" step="100">
                                    <div class="slider">
                                        <div class="track"></div>
                                        <div class="range"></div>
                                        <div class="thumb left"></div>
                                        <div class="thumb right"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between text-muted small">
                                <span>₱0</span>
                                <span>₱5000</span>
                            </div>
                        </div>

                        <!-- Duration Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-medium mb-3">Duration</label>
                            <div class="d-flex flex-column gap-2">
                                <?php
                                $durations = [
                                    '30' => '30 minutes',
                                    '60' => '60 minutes',
                                    '90' => '90 minutes',
                                    '120' => '120 minutes'
                                ];
                                foreach ($durations as $value => $label):
                                ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="duration[]" value="<?php echo $value; ?>" 
                                           id="duration_<?php echo $value; ?>">
                                    <label class="form-check-label" for="duration_<?php echo $value; ?>">
                                        <?php echo $label; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Services List -->
        <div class="col-lg-9">
            <!-- Sort Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="results-count">
                    <h5 class="mb-0"><?php echo count($services); ?> services available</h5>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="text-muted mb-0">Sort by:</label>
                    <select class="form-select form-select-sm" style="width: auto;" id="sortSelect">
                        <option value="popular">Most Popular</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="duration">Duration</option>
                    </select>
                </div>
            </div>

            <!-- Services Grid -->
            <div class="row g-4" id="servicesGrid">
                <?php foreach ($services as $service): ?>
                <div class="col-md-6 col-lg-4 service-item">
                    <div class="card h-100 border-0 shadow-sm service-card">
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
                            <a href="<?php echo BASE_URL; ?>/public/booking?service=<?php echo $service['service_id']; ?>" 
                               class="btn btn-primary w-100 rounded-pill">
                                <i class="bi bi-calendar-check me-2"></i>Book Now
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Styles */
.services-hero {
    background: linear-gradient(135deg, var(--bs-primary) 0%, #4a90e2 100%);
}

.search-wrapper {
    max-width: 600px;
    margin: 0 auto;
}

.service-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 1rem;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.service-image {
    border-radius: 1rem 1rem 0 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.service-meta {
    font-size: 1rem;
    padding: 0.5rem 0;
    border-radius: 0.5rem;
}

.duration-badge {
    color: #2c7be5; /* Blue shade */
    background-color: #e8f1fc;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
}

.price-badge {
    color: #00864e; /* Green shade */
    background-color: #e6f4ed;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
}

.duration-badge i,
.price-badge i {
    opacity: 0.7;
}

.custom-checkbox .form-check-input:checked {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

/* Responsive Adjustments */
@media (max-width: 991.98px) {
    .services-hero {
        padding: 3rem 0;
    }
    
    .col-lg-3 {
        position: fixed;
        left: -100%;
        top: 0;
        height: 100vh;
        z-index: 1050;
        background: white;
        transition: left 0.3s ease;
    }
    
    .col-lg-3.show {
        left: 0;
    }
}

/* Price Range Slider Styles */
.multi-range {
    position: relative;
    height: 30px;
}

.multi-range input[type="range"] {
    position: absolute;
    width: 100%;
    pointer-events: none;
    appearance: none;
    height: 100%;
    opacity: 0;
    z-index: 3;
    padding: 0;
}

.multi-range input[type="range"]::-webkit-slider-thumb {
    pointer-events: all;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 0 none;
    background-color: var(--bs-primary);
    cursor: pointer;
    appearance: none;
}

.multi-range .slider {
    position: absolute;
    width: 100%;
    height: 100%;
}

.multi-range .track {
    position: absolute;
    width: 100%;
    height: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: #e9ecef;
    border-radius: 5px;
}

.multi-range .range {
    position: absolute;
    height: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--bs-primary);
    border-radius: 5px;
}

.multi-range .thumb {
    position: absolute;
    width: 20px;
    height: 20px;
    background: var(--bs-primary);
    border-radius: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    cursor: pointer;
}

.multi-range .thumb.left {
    left: 0;
}

.multi-range .thumb.right {
    right: 0;
    transform: translate(50%, -50%);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceMin = document.getElementById('priceMin');
    const priceMax = document.getElementById('priceMax');
    const priceMinInput = document.getElementById('priceMinInput');
    const priceMaxInput = document.getElementById('priceMaxInput');
    const range = document.querySelector('.multi-range .range');
    const thumbLeft = document.querySelector('.multi-range .thumb.left');
    const thumbRight = document.querySelector('.multi-range .thumb.right');

    function updateRange() {
        const min = parseInt(priceMin.value);
        const max = parseInt(priceMax.value);
        
        // Update range visual
        const left = (min / priceMin.max) * 100;
        const right = 100 - (max / priceMax.max) * 100;
        range.style.left = left + '%';
        range.style.right = right + '%';
        thumbLeft.style.left = left + '%';
        thumbRight.style.right = right + '%';

        // Update input fields
        priceMinInput.value = min;
        priceMaxInput.value = max;
    }

    // Handle slider input
    priceMin.addEventListener('input', function() {
        const minVal = parseInt(priceMin.value);
        const maxVal = parseInt(priceMax.value);
        
        if (minVal > maxVal) {
            priceMin.value = maxVal;
        }
        updateRange();
    });

    priceMax.addEventListener('input', function() {
        const minVal = parseInt(priceMin.value);
        const maxVal = parseInt(priceMax.value);
        
        if (maxVal < minVal) {
            priceMax.value = minVal;
        }
        updateRange();
    });

    // Handle manual input
    priceMinInput.addEventListener('change', function() {
        let value = parseInt(this.value);
        const maxVal = parseInt(priceMax.value);
        
        // Ensure value is within bounds
        value = Math.max(0, Math.min(value, maxVal));
        this.value = value;
        priceMin.value = value;
        updateRange();
    });

    priceMaxInput.addEventListener('change', function() {
        let value = parseInt(this.value);
        const minVal = parseInt(priceMin.value);
        
        // Ensure value is within bounds
        value = Math.max(minVal, Math.min(value, 5000));
        this.value = value;
        priceMax.value = value;
        updateRange();
    });

    // Initialize range visual
    updateRange();

    // Update filter form submission to include price range values
    const filterForm = document.getElementById('filterForm');
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const searchParams = new URLSearchParams();
        
        // Add price range to form data
        formData.append('price_min', priceMin.value);
        formData.append('price_max', priceMax.value);
        
        // Convert FormData to URLSearchParams
        for (const [key, value] of formData.entries()) {
            searchParams.append(key, value);
        }
        
        // Add current sort value if exists
        const currentSort = document.getElementById('sortSelect').value;
        if (currentSort) {
            searchParams.append('sort', currentSort);
        }
        
        // Redirect with filters
        window.location.href = `${window.location.pathname}?${searchParams.toString()}`;
    });

    // Update clear filters to reset price range
    document.getElementById('clearFilters').addEventListener('click', function() {
        filterForm.reset();
        priceMin.value = 0;
        priceMax.value = 5000;
        priceMinInput.value = 0;
        priceMaxInput.value = 5000;
        updateRange();
        window.location.href = window.location.pathname;
    });

    // Enhanced search functionality
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('serviceSearch');

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const currentUrl = new URL(window.location.href);
        const searchTerm = searchInput.value.trim();
        
        if (searchTerm) {
            currentUrl.searchParams.set('search', searchTerm);
        } else {
            currentUrl.searchParams.delete('search');
        }
        
        // Preserve other filters and sort if they exist
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            const formData = new FormData(filterForm);
            for (const [key, value] of formData.entries()) {
                if (value) {
                    currentUrl.searchParams.append(key, value);
                }
            }
        }
        
        window.location.href = currentUrl.toString();
    });

    // Real-time search preview (optional)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = this.value.toLowerCase().trim();
        
        searchTimeout = setTimeout(() => {
            const serviceItems = document.querySelectorAll('.service-item');
            
            serviceItems.forEach(item => {
                const title = item.querySelector('.card-title').textContent.toLowerCase();
                const description = item.querySelector('.card-text').textContent.toLowerCase();
                
                if (searchTerm === '' || title.includes(searchTerm) || description.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }, 300); // Debounce for better performance
    });

    // Sort functionality
    const sortSelect = document.getElementById('sortSelect');
    sortSelect.addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('sort', this.value);
        window.location = url;
    });
});
</script>

<?php include 'templates/footer.php'; ?> 