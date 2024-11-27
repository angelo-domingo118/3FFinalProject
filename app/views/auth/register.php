<?php
// Remove the header include like in login page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SereneBook - Your Path to Wellness. Sign up to book spa and wellness services online.">
    <title>Sign Up - SereneBook™</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>/public/assets/css/styles.css" rel="stylesheet">

    <style>
        /* Reuse most styles from login.php */
        body {
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .register-panel {
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #fff;
            position: relative;
            overflow-y: auto;
        }

        /* Add scrollbar styling for the form panel */
        .register-panel::-webkit-scrollbar {
            width: 6px;
        }

        .register-panel::-webkit-scrollbar-track {
            background: transparent;
        }

        .register-panel::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 20px;
        }

        .register-panel::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0, 0, 0, 0.2);
        }

        /* Rest of the styles from login.php */
        /* ... (copy all relevant styles from login.php) ... */

        /* Additional styles specific to register page */
        .password-requirements {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .password-requirements ul {
            padding-left: 1.2rem;
            margin-bottom: 0;
        }

        .password-requirements li {
            margin-bottom: 0.25rem;
        }

        .password-requirements li.valid {
            color: #198754;
        }

        .terms-text {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .welcome-panel {
            background: linear-gradient(135deg, 
                rgba(0, 123, 255, 0.95), 
                rgba(0, 98, 204, 0.9)), 
                url('<?php echo BASE_URL; ?>/public/assets/images/spa-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 2rem;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .welcome-panel::-webkit-scrollbar {
            width: 6px;
        }

        .welcome-panel::-webkit-scrollbar-track {
            background: transparent;
        }

        .welcome-panel::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            border: transparent;
        }

        .welcome-panel::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .welcome-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 2;
            max-width: 600px;
            margin: 0 auto;
            padding-top: 1rem;
            margin-top: -2rem;
        }

        .welcome-header {
            margin-bottom: 1.5rem;
        }

        .welcome-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .welcome-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.875rem;
            width: 100%;
            margin-top: 1rem;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.25rem;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .feature-item:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.15);
        }

        .feature-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .feature-item h5 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .feature-item p {
            font-size: 0.875rem;
            margin-bottom: 0;
            opacity: 0.8;
        }

        /* Responsive adjustments */
        @media (max-height: 800px) {
            .welcome-content {
                margin-top: -1.5rem;
            }

            .welcome-header {
                margin-bottom: 1rem;
            }

            .feature-item {
                padding: 1rem;
            }

            .features-grid {
                gap: 0.75rem;
            }
        }

        /* Additional animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .floating-element {
            animation: float 6s ease-in-out infinite;
        }

        /* Ensure consistent styling between login and register panels */
        .login-brand a,
        .register-brand a {
            color: #0d6efd;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .login-brand a:hover,
        .register-brand a:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #6c757d;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Register Panel (Left) -->
        <div class="col-lg-5">
            <div class="register-panel">
                <div class="login-brand">
                    <a href="<?php echo BASE_URL; ?>/public">
                        <i class="bi bi-heart-pulse-fill me-2"></i>
                        SereneBook™
                    </a>
                </div>

                <div class="login-form-container">
                    <div class="form-header">
                        <h2>Create Account</h2>
                        <p>Join our wellness community today</p>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php 
                                echo htmlspecialchars($_SESSION['error']);
                                unset($_SESSION['error']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>/public/register" method="POST" class="needs-validation" novalidate>
                        <!-- Full Name -->
                        <div class="mb-4">
                            <label for="fullName" class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person text-muted"></i>
                                </span>
                                <input type="text" class="form-control" id="fullName" name="full_name" 
                                       required placeholder="Enter your full name">
                            </div>
                            <div class="invalid-feedback">Please enter your full name.</div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" 
                                       required placeholder="Enter your email">
                            </div>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phoneNumber" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-telephone text-muted"></i>
                                </span>
                                <input type="tel" class="form-control" id="phoneNumber" name="phone_number" 
                                       required placeholder="Enter 11-digit phone number (e.g., 09123456789)"
                                       minlength="11" maxlength="11"
                                       pattern="[0-9]{11}"
                                       title="Phone number must be exactly 11 digits"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)">
                            </div>
                            <div class="invalid-feedback">Please enter a valid 11-digit phone number.</div>
                            <?php if(isset($_SESSION['error']) && strpos($_SESSION['error'], 'phone number') !== false): ?>
                                <div class="text-danger small mt-1">
                                    <?php 
                                    echo $_SESSION['error']; 
                                    unset($_SESSION['error']); 
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       required placeholder="Create a password"
                                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$">
                                <button class="btn btn-link text-muted px-3" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="password-requirements">
                                <p class="mb-1">Password must contain:</p>
                                <ul>
                                    <li id="length">At least 8 characters</li>
                                    <li id="uppercase">One uppercase letter</li>
                                    <li id="lowercase">One lowercase letter</li>
                                    <li id="number">One number</li>
                                    <li id="special">One special character</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill text-muted"></i>
                                </span>
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" 
                                       required placeholder="Confirm your password">
                            </div>
                            <div class="invalid-feedback">Passwords do not match.</div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label terms-text" for="terms">
                                    I agree to the <a href="#" class="text-primary">Terms of Service</a> and 
                                    <a href="#" class="text-primary">Privacy Policy</a>
                                </label>
                                <div class="invalid-feedback">
                                    You must agree to the terms and conditions.
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-4 py-3">
                            <i class="bi bi-person-plus me-2"></i>Create Account
                        </button>

                        <p class="text-center mb-0">
                            Already have an account? 
                            <a href="<?php echo BASE_URL; ?>/public/login" class="text-primary text-decoration-none fw-medium">
                                Log in
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Welcome Panel (Right) -->
        <div class="col-lg-7">
            <div class="welcome-panel">
                <div class="welcome-content">
                    <div class="welcome-header">
                        <i class="bi bi-peace display-4 mb-2"></i>
                        <h1>Your Path to Wellness</h1>
                        <p>Experience tranquility and rejuvenation with our premium wellness services</p>
                    </div>

                    <div class="features-grid">
                        <div class="feature-item">
                            <i class="bi bi-calendar-check feature-icon"></i>
                            <h5>Easy Booking</h5>
                            <p>Schedule appointments in minutes</p>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-award feature-icon"></i>
                            <h5>Expert Therapists</h5>
                            <p>Certified wellness experts</p>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-heart feature-icon"></i>
                            <h5>Premium Service</h5>
                            <p>Tailored wellness treatments</p>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-shield-check feature-icon"></i>
                            <h5>Safe & Secure</h5>
                            <p>Your privacy is our priority</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
    // Password toggle functionality
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });

    // Password validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    const requirements = {
        length: document.getElementById('length'),
        uppercase: document.getElementById('uppercase'),
        lowercase: document.getElementById('lowercase'),
        number: document.getElementById('number'),
        special: document.getElementById('special')
    };

    password.addEventListener('input', function() {
        const value = this.value;
        
        // Check each requirement
        requirements.length.classList.toggle('valid', value.length >= 8);
        requirements.uppercase.classList.toggle('valid', /[A-Z]/.test(value));
        requirements.lowercase.classList.toggle('valid', /[a-z]/.test(value));
        requirements.number.classList.toggle('valid', /\d/.test(value));
        requirements.special.classList.toggle('valid', /[@$!%*?&]/.test(value));
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
            event.preventDefault();
        } else {
            confirmPassword.setCustomValidity('');
        }

        form.classList.add('was-validated');
    });
</script>

</body>
</html> 