<?php include 'templates/header.php'; ?>

<!-- Hero Section with Video Background -->
<div class="hero-section position-relative overflow-hidden">
    <div class="hero-video-wrapper">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="<?php echo BASE_URL; ?>/public/assets/videos/spa-bg.mp4" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="floating-icons">
            <i class="bi bi-heart-pulse icon"></i>
            <i class="bi bi-stars icon"></i>
            <i class="bi bi-flower1 icon"></i>
            <i class="bi bi-droplet icon"></i>
            <i class="bi bi-peace icon"></i>
            <i class="bi bi-brightness-high icon"></i>
            <i class="bi bi-heart icon"></i>
            <i class="bi bi-flower2 icon"></i>
            <i class="bi bi-wind icon"></i>
            <i class="bi bi-cloud-haze2 icon"></i>
            <i class="bi bi-moon-stars icon"></i>
            <i class="bi bi-gem icon"></i>
            <i class="bi bi-flower3 icon"></i>
            <i class="bi bi-sun icon"></i>
            <i class="bi bi-water icon"></i>
        </div>
    </div>
    <div class="hero-content text-center text-white d-flex align-items-center min-vh-100">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="hero-badge mb-4" data-aos="fade-down" data-aos-duration="1000">
                        <i class="bi bi-stars me-2 star-icon"></i>
                        <span class="badge-text">Premium Wellness Experience</span>
                    </div>
                    <h1 class="hero-title mb-4" data-aos="fade-up" data-aos-duration="1000">
                        Welcome to <span class="text-gradient">SereneBook™</span>
                    </h1>
                    <p class="hero-subtitle mb-5" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                        Your Journey to Wellness Begins Here - Experience Tranquility
                    </p>
                    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                        <a href="<?php echo BASE_URL; ?>/public/booking" 
                           class="btn btn-glow btn-lg px-5 me-3">
                            <i class="bi bi-calendar-check me-2"></i>Book Now
                            <span class="btn-blur"></span>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/public/services" 
                           class="btn btn-glass btn-lg px-5">
                            <i class="bi bi-arrow-right-circle me-2"></i>Explore Services
                            <span class="btn-shine"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator">
        <div class="mouse">
            <div class="wheel"></div>
        </div>
        <div class="arrow-wrapper">
            <span class="arrow"></span>
        </div>
    </div>
</div>

<style>
    .hero-section {
        position: relative;
        background-color: #000;
        height: 100vh;
    }

    .hero-video-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .hero-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.65;
        transform: scale(1.1);
        transition: transform 8s ease;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            45deg,
            rgba(0, 0, 0, 0.7),
            rgba(0, 0, 0, 0.5)
        );
    }

    .hero-content {
        position: relative;
        z-index: 3;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.7rem 1.8rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 0.95rem;
        font-weight: 500;
        animation: float 3s ease-in-out infinite;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .star-icon {
        color: #ffd700;
        animation: twinkle 1.5s infinite;
    }

    .hero-title {
        font-size: 4.5rem;
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -0.02em;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        margin-bottom: 1.5rem;
    }

    .text-gradient {
        background: linear-gradient(135deg, #fff 0%, #a8c0ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.35rem;
        font-weight: 400;
        line-height: 1.6;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .hero-buttons {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
    }

    .btn-glow {
        position: relative;
        background: linear-gradient(45deg, var(--bs-primary), #4a90e2);
        border: none;
        border-radius: 50px;
        color: white;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 4px 15px rgba(var(--bs-primary-rgb), 0.3);
    }

    .btn-glow:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(var(--bs-primary-rgb), 0.4);
    }

    .btn-glass {
        position: relative;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        color: white;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px);
    }

    .btn-shine {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent,
            rgba(255, 255, 255, 0.1),
            transparent
        );
        transform: rotate(45deg);
        animation: shine 3s infinite;
    }

    .hero-scroll-indicator {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 3;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .mouse {
        width: 26px;
        height: 42px;
        border: 2px solid white;
        border-radius: 15px;
        position: relative;
        margin: 0 auto 10px;
    }

    .wheel {
        width: 4px;
        height: 8px;
        background: white;
        position: absolute;
        top: 8px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 2px;
        animation: scroll 2s infinite;
    }

    .arrow-wrapper {
        animation: bounce 2s infinite;
    }

    .arrow {
        display: block;
        width: 10px;
        height: 10px;
        border-right: 2px solid white;
        border-bottom: 2px solid white;
        transform: rotate(45deg);
        margin: -5px auto 0;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    @keyframes twinkle {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    @keyframes shine {
        0% { left: -50%; }
        100% { left: 150%; }
    }

    @keyframes scroll {
        0% { opacity: 1; transform: translateX(-50%) translateY(0); }
        100% { opacity: 0; transform: translateX(-50%) translateY(15px); }
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.75rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
            padding: 0 1rem;
        }

        .hero-buttons {
            flex-direction: column;
            gap: 1rem;
            padding: 0 1rem;
        }

        .btn {
            width: 100%;
        }

        .hero-scroll-indicator {
            display: none;
        }
    }

    .floating-icons {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    }

    .floating-icons .icon {
        position: absolute;
        color: rgba(255, 255, 255, 0.15);
        filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
        pointer-events: none;
        transition: all 0.3s ease;
    }

    /* First Layer - Closest */
    .floating-icons .icon:nth-child(1) {
        top: 15%;
        left: 10%;
        animation: floatingClose 8s ease-in-out infinite, glowing 2s ease-in-out infinite;
        font-size: 2.2rem;
    }

    .floating-icons .icon:nth-child(2) {
        top: 20%;
        right: 15%;
        animation: floatingClose 9s ease-in-out infinite, glowing 3s ease-in-out infinite;
        font-size: 2rem;
    }

    /* Second Layer - Middle distance */
    .floating-icons .icon:nth-child(3) {
        top: 45%;
        left: 20%;
        animation: floatingMid 12s ease-in-out infinite;
        font-size: 1.8rem;
        opacity: 0.12;
    }

    .floating-icons .icon:nth-child(4) {
        top: 35%;
        right: 25%;
        animation: floatingMid 11s ease-in-out infinite;
        font-size: 1.7rem;
        opacity: 0.12;
    }

    /* Third Layer - Farthest */
    .floating-icons .icon:nth-child(5) {
        top: 65%;
        left: 15%;
        animation: floatingFar 15s ease-in-out infinite;
        font-size: 1.4rem;
        opacity: 0.1;
    }

    /* Add positions for new icons */
    .floating-icons .icon:nth-child(6) { top: 75%; right: 18%; }
    .floating-icons .icon:nth-child(7) { top: 30%; left: 25%; }
    .floating-icons .icon:nth-child(8) { top: 85%; right: 22%; }
    .floating-icons .icon:nth-child(9) { top: 40%; left: 30%; }
    .floating-icons .icon:nth-child(10) { top: 55%; right: 28%; }
    .floating-icons .icon:nth-child(11) { top: 25%; left: 35%; }
    .floating-icons .icon:nth-child(12) { top: 70%; right: 32%; }
    .floating-icons .icon:nth-child(13) { top: 45%; left: 40%; }
    .floating-icons .icon:nth-child(14) { top: 60%; right: 38%; }
    .floating-icons .icon:nth-child(15) { top: 35%; left: 45%; }

    /* Enhanced Animation Keyframes */
    @keyframes floatingClose {
        0%, 100% {
            transform: translate(0, 0) rotate(0deg) scale(1);
        }
        25% {
            transform: translate(10px, -15px) rotate(5deg) scale(1.05);
        }
        50% {
            transform: translate(20px, 0) rotate(0deg) scale(1);
        }
        75% {
            transform: translate(10px, 15px) rotate(-5deg) scale(0.95);
        }
    }

    @keyframes floatingMid {
        0%, 100% {
            transform: translate(0, 0) rotate(0deg);
        }
        25% {
            transform: translate(15px, -20px) rotate(10deg);
        }
        50% {
            transform: translate(30px, 0) rotate(0deg);
        }
        75% {
            transform: translate(15px, 20px) rotate(-10deg);
        }
    }

    @keyframes floatingFar {
        0%, 100% {
            transform: translate(0, 0) rotate(0deg) scale(1);
        }
        33% {
            transform: translate(20px, -25px) rotate(15deg) scale(1.1);
        }
        66% {
            transform: translate(40px, 25px) rotate(-15deg) scale(0.9);
        }
    }

    @keyframes glowing {
        0%, 100% {
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
        }
        50% {
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }
    }

    /* Add random animation delays to create more natural movement */
    .floating-icons .icon:nth-child(3n) { animation-delay: -2s; }
    .floating-icons .icon:nth-child(3n+1) { animation-delay: -4s; }
    .floating-icons .icon:nth-child(3n+2) { animation-delay: -6s; }

    /* Add hover effect on parent container */
    .hero-section:hover .floating-icons .icon {
        animation-play-state: paused;
        transition: all 0.3s ease;
    }
</style>

<!-- Features Section -->
<section class="features-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill">
                <i class="bi bi-stars me-1"></i>Why Choose Us
            </span>
            <h2 class="display-5 fw-bold mb-3">Our Features</h2>
            <p class="lead text-dark mb-0">Experience the difference with our premium wellness services</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon-wrapper mb-4">
                        <div class="feature-icon-bg">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                    <h4 class="feature-title mb-3">Easy Booking</h4>
                    <p class="feature-text mb-0">Book your wellness session in minutes, anytime, anywhere</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon-wrapper mb-4">
                        <div class="feature-icon-bg">
                            <i class="bi bi-award"></i>
                        </div>
                    </div>
                    <h4 class="feature-title mb-3">Expert Therapists</h4>
                    <p class="feature-text mb-0">Certified professionals dedicated to your wellness journey</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon-wrapper mb-4">
                        <div class="feature-icon-bg">
                            <i class="bi bi-heart"></i>
                        </div>
                    </div>
                    <h4 class="feature-title mb-3">Personalized Care</h4>
                    <p class="feature-text mb-0">Tailored treatments to meet your unique wellness needs</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .features-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            position: relative;
            overflow: hidden;
            padding: 5rem 0;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .feature-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        .feature-icon-bg {
            width: 80px;
            height: 80px;
            background: var(--bs-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: white;
            font-size: 2.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
        }

        .feature-card:hover .feature-icon-bg {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
        }

        .feature-title {
            color: var(--bs-dark);
            font-weight: 600;
        }

        .feature-text {
            color: var(--bs-gray-600);
            font-size: 1rem;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .feature-card {
                margin-bottom: 1rem;
            }
            
            .feature-icon-bg {
                width: 70px;
                height: 70px;
            }

            .feature-icon-bg i {
                font-size: 2rem;
            }
        }
    </style>
</section>

<!-- Featured Services Section -->
<section class="services-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill">
                <i class="bi bi-stars me-1"></i>Our Services
            </span>
            <h2 class="display-5 fw-bold mb-3">Wellness Services</h2>
            <p class="lead text-muted">Discover our range of therapeutic treatments designed for your wellbeing</p>
        </div>

        <!-- Service Categories Pills -->
        <div class="service-categories mb-5">
            <div class="row g-3 justify-content-center">
                <?php
                $categories = [
                    [
                        'type' => 'massage',
                        'icon' => 'bi-hand-index-thumb',
                        'name' => 'Massage Therapy',
                        'color' => 'primary'
                    ],
                    [
                        'type' => 'facial',
                        'icon' => 'bi-stars',
                        'name' => 'Facial Care',
                        'color' => 'info'
                    ],
                    [
                        'type' => 'body',
                        'icon' => 'bi-droplet',
                        'name' => 'Body Treatments',
                        'color' => 'success'
                    ],
                    [
                        'type' => 'aromatherapy',
                        'icon' => 'bi-flower2',
                        'name' => 'Aromatherapy',
                        'color' => 'warning'
                    ]
                ];
                
                foreach ($categories as $category):
                ?>
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="category-card text-center p-4 h-100 bg-white rounded-4 shadow-sm hover-lift">
                        <div class="category-icon-wrapper mb-3">
                            <div class="category-icon bg-<?php echo $category['color']; ?>-subtle text-<?php echo $category['color']; ?> rounded-circle">
                                <i class="bi <?php echo $category['icon']; ?>"></i>
                            </div>
                        </div>
                        <h4 class="category-title h5 mb-3"><?php echo $category['name']; ?></h4>
                        <div class="category-stats d-flex justify-content-around">
                            <div class="stat-item">
                                <small class="text-dark-emphasis d-block">From</small>
                                <span class="fw-bold text-<?php echo $category['color']; ?>">₱1,500</span>
                            </div>
                            <div class="stat-item">
                                <small class="text-dark-emphasis d-block">Duration</small>
                                <span class="fw-bold text-<?php echo $category['color']; ?>">60min</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Featured Services Cards -->
        <div class="featured-services">
            <div class="row g-4">
                <?php if (!empty($all_services)): ?>
                    <?php foreach (array_slice($all_services, 0, 3) as $service): ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up">
                        <div class="service-card bg-white rounded-4 p-4 position-relative hover-lift d-flex flex-column h-100">
                            <?php if ($service['is_popular']): ?>
                            <div class="popular-badge position-absolute">
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                    <i class="bi bi-star-fill me-1"></i>Popular Choice
                                </span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="service-icon-wrapper mb-4">
                                <?php
                                $iconClass = match($service['service_type']) {
                                    'massage' => 'bi-hand-index-thumb text-primary',
                                    'facial' => 'bi-stars text-info',
                                    'body' => 'bi-droplet text-success',
                                    default => 'bi-flower2 text-warning'
                                };
                                ?>
                                <div class="service-icon bg-light rounded-circle">
                                    <i class="bi <?php echo $iconClass; ?>"></i>
                                </div>
                            </div>
                            
                            <div class="service-content flex-grow-1">
                                <h3 class="service-title h5 mb-3">
                                    <?php echo htmlspecialchars($service['service_name']); ?>
                                </h3>
                                
                                <div class="service-meta mb-3">
                                    <span class="badge bg-light text-dark me-2">
                                        <i class="bi bi-clock me-1"></i><?php echo $service['duration']; ?> mins
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-tag me-1"></i>₱<?php echo number_format($service['price'], 2); ?>
                                    </span>
                                </div>
                                
                                <p class="service-description text-dark-emphasis mb-4">
                                    <?php echo htmlspecialchars($service['description']); ?>
                                </p>
                            </div>
                            
                            <div class="service-action mt-auto pt-3">
                                <a href="<?php echo BASE_URL; ?>/public/booking?service=<?php echo $service['service_id']; ?>" 
                                   class="btn btn-primary w-100 rounded-pill">
                                    <i class="bi bi-calendar-check me-2"></i>Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <div class="empty-state p-5">
                            <i class="bi bi-calendar-x display-1 text-muted mb-3"></i>
                            <p class="lead text-muted">No services available at the moment.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- View All Services Button -->
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?php echo BASE_URL; ?>/public/services" 
               class="btn btn-outline-primary btn-lg rounded-pill px-5 hover-lift">
                <i class="bi bi-grid me-2"></i>View All Services
            </a>
        </div>
    </div>
</section>

<style>
/* Enhanced Styles */
.services-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.category-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}

.category-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 2rem;
}

.service-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}

.service-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.popular-badge {
    top: 1rem;
    right: 1rem;
}

.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.empty-state {
    background: rgba(0,0,0,0.02);
    border-radius: 1rem;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .category-card {
        margin-bottom: 1rem;
    }
    
    .service-card {
        margin-bottom: 1.5rem;
    }
}
</style>

<!-- Testimonials Section -->
<section class="testimonials-section py-6 bg-light position-relative">
    <div class="section-overlay"></div>
    <div class="container position-relative">
        <div class="text-center mb-5">
            <div class="section-badge mb-3" data-aos="fade-down">
                <span>TESTIMONIALS</span>
            </div>
            <h2 class="display-5 fw-bold text-dark mb-2" data-aos="fade-up">Client Stories</h2>
            <p class="lead text-dark-emphasis mb-5" data-aos="fade-up" data-aos-delay="100">
                Real experiences from our valued clients
            </p>
        </div>

        <style>
            .testimonials-section {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                padding: 7rem 0;
                position: relative;
                overflow: hidden;
            }

            .section-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.02);
            }

            .section-badge {
                display: inline-block;
                padding: 0.5rem 1.5rem;
                background: rgba(var(--bs-primary-rgb), 0.1);
                color: var(--bs-primary);
                border-radius: 50px;
                font-weight: 600;
                font-size: 0.9rem;
                letter-spacing: 1px;
            }

            .text-dark-emphasis {
                color: #495057;
            }

            /* Increase spacing between header and testimonials */
            .testimonials-section .text-center.mb-5 {
                margin-bottom: 4rem !important;
            }

            /* Update existing testimonial card styles */
            .testimonial-card {
                background: rgba(255, 255, 255, 0.9);
                border-radius: 20px;
                padding: 3rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            }
        </style>

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
<section class="cta-section py-5 text-white text-center position-relative overflow-hidden">
    <div class="cta-video-wrapper">
        <video autoplay muted loop playsinline class="cta-video">
            <source src="<?php echo BASE_URL; ?>/public/assets/videos/spa-bg.mp4" type="video/mp4">
        </video>
        <div class="cta-overlay"></div>
        <div class="floating-icons">
            <i class="bi bi-heart-pulse icon"></i>
            <i class="bi bi-stars icon"></i>
            <i class="bi bi-flower1 icon"></i>
            <i class="bi bi-droplet icon"></i>
            <i class="bi bi-peace icon"></i>
            <i class="bi bi-brightness-high icon"></i>
            <i class="bi bi-heart icon"></i>
            <i class="bi bi-flower2 icon"></i>
        </div>
    </div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="hero-badge mb-4" data-aos="fade-down" data-aos-duration="1000">
            <i class="bi bi-stars me-2 star-icon"></i>
            <span class="badge-text">Join Our Community</span>
        </div>
        <h2 class="display-5 fw-bold mb-4" data-aos="fade-up" data-aos-duration="1000">
            Start Your Wellness Journey Today
        </h2>
        <p class="lead mb-5" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
            Join our community of wellness enthusiasts and transform your life
        </p>
        <div class="cta-buttons" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
            <a href="<?php echo BASE_URL; ?>/public/register" 
               class="btn btn-glow btn-lg px-5 me-3">
                <i class="bi bi-person-plus me-2"></i>Create Account
                <span class="btn-blur"></span>
            </a>
            <a href="<?php echo BASE_URL; ?>/public/booking" 
               class="btn btn-glass btn-lg px-5">
                <i class="bi bi-calendar-check me-2"></i>Book Session
                <span class="btn-shine"></span>
            </a>
        </div>
    </div>
    <style>
        .cta-section {
            position: relative;
            min-height: 60vh;
            display: flex;
            align-items: center;
            background-color: #000;
        }

        .cta-video-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .cta-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.65;
        }

        .cta-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                45deg,
                rgba(0, 0, 0, 0.7),
                rgba(0, 0, 0, 0.5)
            );
        }
    </style>
</section>

<?php include 'templates/footer.php'; ?> 