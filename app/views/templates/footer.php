    </div>
    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Brand Column -->
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <a class="footer-brand d-flex align-items-center mb-3" href="<?php echo BASE_URL; ?>/public">
                        <i class="bi bi-heart-pulse-fill text-primary me-2 brand-icon"></i>
                        <span class="fs-4 fw-semibold">SereneBook™</span>
                    </a>
                    <p class="text-muted mb-4">Your trusted partner in wellness and relaxation. Book your path to serenity today.</p>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-sm-6 col-lg-2 mb-4 mb-lg-0">
                    <h5 class="mb-4">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><a href="<?php echo BASE_URL; ?>/public/about">About Us</a></li>
                        <li class="mb-3"><a href="<?php echo BASE_URL; ?>/public/services">Services</a></li>
                        <li class="mb-3"><a href="<?php echo BASE_URL; ?>/public/booking">Book Now</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/contact">Contact</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="col-sm-6 col-lg-3 mb-4 mb-lg-0">
                    <h5 class="mb-4">Our Services</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><a href="<?php echo BASE_URL; ?>/public/services#massage">Massage Therapy</a></li>
                        <li class="mb-3"><a href="<?php echo BASE_URL; ?>/public/services#facial">Facial Treatments</a></li>
                        <li class="mb-3"><a href="<?php echo BASE_URL; ?>/public/services#yoga">Yoga Classes</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/services#meditation">Meditation Sessions</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="col-lg-3">
                    <h5 class="mb-4">Newsletter</h5>
                    <p class="text-muted mb-4">Subscribe to receive updates and special offers.</p>
                    <form class="newsletter-form">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Your email" aria-label="Your email">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <p class="mb-0">&copy; <?php echo date('Y'); ?> SereneBook™. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="<?php echo BASE_URL; ?>/public/privacy">Privacy Policy</a></li>
                            <li class="list-inline-item">·</li>
                            <li class="list-inline-item"><a href="<?php echo BASE_URL; ?>/public/terms">Terms of Use</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/assets/js/scripts.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>

    <style>
        .footer {
            background: linear-gradient(135deg, #2b2d42 0%, #1a1b2e 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
            padding-top: 5rem;
            padding-bottom: 3rem;
            margin-top: 0;
        }

        /* Add decorative top edge */
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                var(--bs-primary) 0%,
                #4d84e2 50%,
                var(--bs-primary) 100%);
        }

        /* Update wave pattern overlay */
        .footer::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('<?php echo BASE_URL; ?>/public/assets/images/pattern-bg.png') repeat;
            opacity: 0.04;
            pointer-events: none;
        }

        .footer-brand {
            text-decoration: none;
            color: #fff;
            transition: all 0.3s ease;
        }

        .footer-brand:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .footer h5 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        /* Add decorative underline to headings */
        .footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--bs-primary);
        }

        .footer ul li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            padding: 0.3rem 0;
        }

        .footer ul li a:hover {
            color: #fff;
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 1.25rem;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .social-links a:hover {
            color: #fff;
            transform: translateY(-3px);
            background: var(--bs-primary);
        }

        .newsletter-form .form-control {
            border-radius: 50px 0 0 50px;
            border: none;
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .newsletter-form .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .newsletter-form .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: none;
        }

        .newsletter-form .btn {
            border-radius: 0 50px 50px 0;
            padding: 0.75rem 1.5rem;
            background: var(--bs-primary);
            border: none;
        }

        .newsletter-form .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.4);
        }

        .footer-bottom {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-bottom a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-bottom a:hover {
            color: #fff;
        }

        /* Update text colors */
        .text-muted {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        @media (max-width: 991.98px) {
            .footer {
                padding-top: 4rem;
                padding-bottom: 2rem;
            }
            
            .footer-bottom {
                margin-top: 3rem;
            }
        }

        /* Remove the hr element from HTML and its related styles */
        .footer-bottom hr {
            display: none;
        }
    </style>
</body>
</html> 