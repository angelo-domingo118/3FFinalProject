<?php include 'templates/header.php'; ?>

<!-- Hero Section with Video Background -->
<div class="hero-section position-relative overflow-hidden">
    <div class="hero-video-wrapper">
        <video autoplay muted loop class="hero-video">
            <source src="<?php echo BASE_URL; ?>/public/assets/videos/spa-bg.mp4" type="video/mp4">
        </video>
    </div>
    <div class="hero-content text-center text-white d-flex align-items-center min-vh-100">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-2 fw-bold mb-4" data-aos="fade-up">Welcome to SereneBook™</h1>
                    <p class="lead mb-5 fw-light" data-aos="fade-up" data-aos-delay="100">Your Journey to Wellness Begins Here - Experience Tranquility</p>
                    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="200">
                        <a href="/booking" class="btn btn-primary btn-lg px-5 me-3 rounded-pill">Book Now</a>
                        <a href="/services" class="btn btn-outline-light btn-lg px-5 rounded-pill">Explore Services</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-sm-6">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h4 class="mb-3">Easy Booking</h4>
                    <p class="text-muted mb-0">Book your wellness session in minutes, anytime, anywhere</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h4 class="mb-3">Expert Therapists</h4>
                    <p class="text-muted mb-0">Certified professionals dedicated to your wellness journey</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h4 class="mb-3">Personalized Care</h4>
                    <p class="text-muted mb-0">Tailored treatments to meet your unique wellness needs</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Services Section -->
<section class="services-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Our Wellness Services</h2>
            <p class="lead text-muted">Discover our range of therapeutic treatments designed for your wellbeing</p>
        </div>

        <!-- Service Categories -->
        <div class="service-categories mb-5">
            <div class="row g-4 justify-content-center text-center">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="category-pill active">
                        <i class="bi bi-hand-index-thumb me-2"></i>
                        Massage Therapy
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="category-pill">
                        <i class="bi bi-flower2 me-2"></i>
                        Aromatherapy
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="category-pill">
                        <i class="bi bi-droplet me-2"></i>
                        Body Treatments
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="category-pill">
                        <i class="bi bi-stars me-2"></i>
                        Facial Care
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Cards -->
        <div class="row g-4">
            <!-- Swedish Massage -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="service-card">
                    <div class="service-image-wrapper">
                        <div class="service-image service-bg-1">
                            <div class="service-overlay"></div>
                        </div>
                        <span class="service-badge">Most Popular</span>
                        <div class="service-info">
                            <span class="service-duration">
                                <i class="bi bi-clock"></i> 60 mins
                            </span>
                            <span class="service-level">
                                <i class="bi bi-activity"></i> Gentle
                            </span>
                        </div>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">Swedish Massage</h3>
                        <p class="service-description">Experience deep relaxation with our signature Swedish massage, perfect for stress relief and muscle tension.</p>
                        <div class="service-meta">
                            <div class="service-price">
                                <span class="currency">₱</span>
                                <span class="amount">1,500</span>
                            </div>
                            <a href="/booking?service=1" class="btn btn-primary rounded-pill">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aromatherapy Massage -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="service-card">
                    <div class="service-image-wrapper">
                        <div class="service-image service-bg-2">
                            <div class="service-overlay"></div>
                        </div>
                        <span class="service-badge bg-info">Featured</span>
                        <div class="service-info">
                            <span class="service-duration">
                                <i class="bi bi-clock"></i> 90 mins
                            </span>
                            <span class="service-level">
                                <i class="bi bi-activity"></i> Moderate
                            </span>
                        </div>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">Aromatherapy Massage</h3>
                        <p class="service-description">A therapeutic blend of essential oils and massage techniques to enhance physical and emotional well-being.</p>
                        <div class="service-meta">
                            <div class="service-price">
                                <span class="currency">₱</span>
                                <span class="amount">2,000</span>
                            </div>
                            <a href="/booking?service=2" class="btn btn-primary rounded-pill">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hot Stone Therapy -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="service-card">
                    <div class="service-image-wrapper">
                        <div class="service-image service-bg-3">
                            <div class="service-overlay"></div>
                        </div>
                        <span class="service-badge bg-warning">Premium</span>
                        <div class="service-info">
                            <span class="service-duration">
                                <i class="bi bi-clock"></i> 120 mins
                            </span>
                            <span class="service-level">
                                <i class="bi bi-activity"></i> Intensive
                            </span>
                        </div>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">Hot Stone Therapy</h3>
                        <p class="service-description">Melt away tension with heated volcanic stones combined with therapeutic massage techniques.</p>
                        <div class="service-meta">
                            <div class="service-price">
                                <span class="currency">₱</span>
                                <span class="amount">2,500</span>
                            </div>
                            <a href="/booking?service=3" class="btn btn-primary rounded-pill">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View All Services Button -->
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="/services" class="btn btn-outline-primary btn-lg rounded-pill px-5">
                View All Services <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials Section with Rating -->
<section class="testimonials-section py-5">
    <div class="container">
        <div class="section-header text-center mb-3" data-aos="fade-up">
            <span class="section-subtitle">TESTIMONIALS</span>
            <h2 class="display-5 fw-bold mb-3">Client Stories</h2>
            <p class="lead text-muted">Real experiences from our valued clients</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div id="testimonialCarousel" class="carousel slide testimonial-carousel" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="testimonial-wrapper position-relative">
                        <button class="carousel-control carousel-control-prev" type="button" 
                                data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <div class="carousel-inner">
                            <!-- Testimonial 1 -->
                            <div class="carousel-item active">
                                <div class="testimonial-wrapper">
                                    <div class="testimonial-card">
                                        <div class="quote-icon">
                                            <i class="bi bi-quote text-primary opacity-25 display-1"></i>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <div class="testimonial-author text-center">
                                                    <div class="author-image-wrapper mb-3">
                                                        <div class="author-image">
                                                            <i class="bi bi-person-circle"></i>
                                                        </div>
                                                        <div class="author-badge">
                                                            <i class="bi bi-patch-check-fill text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <h5 class="mb-1">Sarah Johnson</h5>
                                                    <p class="text-muted mb-2">Regular Client</p>
                                                    <div class="rating-stars">
                                                        <div class="stars-outer">
                                                            <div class="stars-inner" style="width: 100%"></div>
                                                        </div>
                                                        <span class="rating-number">5.0</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="testimonial-content">
                                                    <p class="testimonial-text">"The booking process was seamless, and the massage therapy was exactly what I needed. The therapist was professional and attentive to my needs. The ambiance was perfect for relaxation. Highly recommended!"</p>
                                                    <div class="testimonial-meta">
                                                        <span class="service-type">
                                                            <i class="bi bi-award-fill text-primary me-2"></i>
                                                            Swedish Massage
                                                        </span>
                                                        <span class="verified-badge">
                                                            <i class="bi bi-shield-check text-success me-2"></i>
                                                            Verified Client
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Testimonial 2 -->
                            <div class="carousel-item">
                                <div class="testimonial-wrapper">
                                    <div class="testimonial-card">
                                        <div class="quote-icon">
                                            <i class="bi bi-quote text-primary opacity-25 display-1"></i>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <div class="testimonial-author text-center">
                                                    <div class="author-image-wrapper mb-3">
                                                        <div class="author-image">
                                                            <i class="bi bi-person-circle"></i>
                                                        </div>
                                                        <div class="author-badge">
                                                            <i class="bi bi-patch-check-fill text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <h5 class="mb-1">Michael Chen</h5>
                                                    <p class="text-muted mb-2">VIP Member</p>
                                                    <div class="rating-stars">
                                                        <div class="stars-outer">
                                                            <div class="stars-inner" style="width: 95%"></div>
                                                        </div>
                                                        <span class="rating-number">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="testimonial-content">
                                                    <p class="testimonial-text">"The aromatherapy session was incredible! The essential oils selection was perfect, and the therapist's technique was outstanding. I left feeling completely rejuvenated and stress-free."</p>
                                                    <div class="testimonial-meta">
                                                        <span class="service-type">
                                                            <i class="bi bi-award-fill text-primary me-2"></i>
                                                            Aromatherapy Massage
                                                        </span>
                                                        <span class="verified-badge">
                                                            <i class="bi bi-shield-check text-success me-2"></i>
                                                            Verified Client
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Testimonial 3 -->
                            <div class="carousel-item">
                                <div class="testimonial-wrapper">
                                    <div class="testimonial-card">
                                        <div class="quote-icon">
                                            <i class="bi bi-quote text-primary opacity-25 display-1"></i>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <div class="testimonial-author text-center">
                                                    <div class="author-image-wrapper mb-3">
                                                        <div class="author-image">
                                                            <i class="bi bi-person-circle"></i>
                                                        </div>
                                                        <div class="author-badge">
                                                            <i class="bi bi-patch-check-fill text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <h5 class="mb-1">Emma Rodriguez</h5>
                                                    <p class="text-muted mb-2">Monthly Member</p>
                                                    <div class="rating-stars">
                                                        <div class="stars-outer">
                                                            <div class="stars-inner" style="width: 90%"></div>
                                                        </div>
                                                        <span class="rating-number">4.5</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="testimonial-content">
                                                    <p class="testimonial-text">"The hot stone therapy was a game-changer for my chronic back pain. The combination of heated stones and expert massage techniques provided long-lasting relief. The staff is incredibly knowledgeable and caring."</p>
                                                    <div class="testimonial-meta">
                                                        <span class="service-type">
                                                            <i class="bi bi-award-fill text-primary me-2"></i>
                                                            Hot Stone Therapy
                                                        </span>
                                                        <span class="verified-badge">
                                                            <i class="bi bi-shield-check text-success me-2"></i>
                                                            Verified Client
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control carousel-control-next" type="button" 
                                data-bs-target="#testimonialCarousel" data-bs-slide="next">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                        <div class="carousel-indicators testimonial-indicators">
                            <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" 
                                    class="active bg-primary" aria-current="true"></button>
                            <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1" 
                                    class="bg-primary"></button>
                            <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="2" 
                                    class="bg-primary"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="cta-section py-5 text-white text-center">
    <div class="container">
        <h2 class="display-5 fw-bold mb-4">Start Your Wellness Journey Today</h2>
        <p class="lead mb-5">Join our community of wellness enthusiasts and transform your life</p>
        <div class="cta-buttons">
            <a href="/register" class="btn btn-light btn-lg px-5 me-3 rounded-pill">Create Account</a>
            <a href="/booking" class="btn btn-outline-light btn-lg px-5 rounded-pill">Book Session</a>
        </div>
    </div>
</section>

<?php include 'templates/footer.php'; ?> 